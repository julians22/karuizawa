<?php

namespace App\Jobs;

use App\Models\OrderShipment;
use App\Services\Shipping\BiteshipService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateBiteshipShipmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected int $orderShipmentId)
    {
    }

    public $tries = 1;

    public function handle(BiteshipService $biteship): void
    {
        $shipment = OrderShipment::with('order.orderItems.product')->find($this->orderShipmentId);

        if (!$shipment || $shipment->biteship_order_id) {
            return;
        }

        $order = $shipment->order;

        $items = $order->orderItems->map(function ($item) {
            return [
                'name' => $item->product->product_name ?? $item->product->name ?? 'Item',
                'description' => $item->product->product_name ?? $item->product->name ?? 'Item',
                'value' => (float) $item->price,
                'quantity' => (int) $item->quantity,
                'weight' => (int) config('biteship.default_item_weight_gram'),
            ];
        })->values()->all();

        $payload = [
            'origin_contact_name' => config('biteship.origin.contact_name'),
            'origin_contact_phone' => config('biteship.origin.contact_phone'),
            'origin_address' => config('biteship.origin.address'),
            'origin_note' => config('biteship.origin.note'),
            'origin_area_id' => config('biteship.origin.area_id'),
            'origin_postal_code' => config('biteship.origin.postal_code'),

            'destination_contact_name' => $shipment->recipient_name,
            'destination_contact_phone' => $shipment->recipient_phone,
            'destination_contact_email' => $shipment->recipient_email ?? $order->customer?->email,
            'destination_address' => $shipment->recipient_address,
            'destination_area_id' => $shipment->area_id,
            'destination_postal_code' => $shipment->postal_code,
            'destination_latitude' => $shipment->latitude,
            'destination_longitude' => $shipment->longitude,
            'destination_note' => $shipment->note,

            'courier_company' => $shipment->courier_company,
            'courier_type' => $shipment->courier_type,
            'delivery_type' => 'now',
            'order_note' => 'Order ' . $order->order_number,
            'reference_id' => $order->order_number,

            'items' => $items,
        ];

        try {
            $result = $biteship->createOrder($payload);
        } catch (\Throwable $th) {
            // Network/API errors must not crash the job - persist the failure so it's visible in the shipment record.
            Log::error('Biteship createOrder threw an exception for order ' . $order->order_number . ': ' . $th->getMessage());
            $result = ['success' => false, 'request' => $payload, 'response' => ['error' => $th->getMessage()]];
        }

        $response = $result['response'] ?? [];

        $updateData = [
            'raw_request' => $result['request'] ?? $payload,
            'raw_response' => $response,
        ];

        if ($result['success'] ?? false) {
            $updateData['biteship_order_id'] = $response['id'] ?? null;
            $updateData['biteship_tracking_id'] = $response['courier']['tracking_id'] ?? null;
            $updateData['waybill_id'] = $response['courier']['waybill_id'] ?? null;
            $updateData['status'] = $response['status'] ?? OrderShipment::STATUS_CONFIRMED;
        } else {
            $updateData['status'] = OrderShipment::STATUS_COURIER_NOT_FOUND;
            Log::error('Failed to create Biteship shipment for order ' . $order->order_number, $response);
        }

        $shipment->update($updateData);

        $shipment->histories()->create([
            'status' => $updateData['status'],
            'note' => $result['success'] ?? false ? 'Shipment created on Biteship' : 'Failed to create shipment on Biteship',
            'raw_payload' => $response,
        ]);
    }
}
