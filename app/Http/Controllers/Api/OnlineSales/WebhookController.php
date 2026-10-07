<?php

namespace App\Http\Controllers\Api\OnlineSales;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle Biteship order status and waybill ID webhooks.
     *
     * @see https://biteship.com/id/docs/api/webhook/overview
     */
    public function biteship(Request $request)
    {
        $token = config('biteship.webhook_token');

        if ($token && $request->header('Authorization') !== $token) {
            Log::warning('Rejected Biteship webhook with invalid token');

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? null;

        if (!in_array($event, ['order.status', 'order.waybill_id'], true)) {
            Log::warning('Rejected Biteship webhook with unsupported event', [
                'event' => $event,
                'payload' => $payload,
            ]);

            return response()->json(['success' => false, 'message' => 'Invalid payload'], 422);
        }

        $biteshipOrderId = $payload['order_id'] ?? null;
        $waybillId = $payload['courier_waybill_id'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$biteshipOrderId || ($event === 'order.status' && !$status) || ($event === 'order.waybill_id' && !$waybillId)) {
            Log::warning('Rejected Biteship webhook with missing required fields', [
                'event' => $event,
                'payload' => $payload,
            ]);

            return response()->json(['success' => false, 'message' => 'Invalid payload'], 422);
        }

        $shipment = OrderShipment::where('biteship_order_id', $biteshipOrderId)->first();

        if (!$shipment) {
            Log::warning('Biteship webhook for unknown order', ['order_id' => $biteshipOrderId]);

            return response()->json(['success' => false, 'message' => 'Shipment not found'], 404);
        }

        if ($event === 'order.status') {
            $shipment->update([
                'status' => $status,
                'biteship_tracking_id' => $payload['courier_tracking_id'] ?? $shipment->biteship_tracking_id,
                'waybill_id' => $waybillId ?? $shipment->waybill_id,
                'raw_response' => $payload,
            ]);

            $shipment->histories()->create([
                'status' => $status,
                'note' => $payload['courier_note'] ?? null,
                'raw_payload' => $payload,
            ]);

            if ($status === OrderShipment::STATUS_DELIVERED) {
                $shipment->order()->update([
                    'status' => config('enums.order_status.completed'),
                ]);
            }

            if ($status === OrderShipment::STATUS_CANCELLED) {
                $shipment->order()->update([
                    'status' => config('enums.order_status.cancelled'),
                ]);
            }
        } else {
            $shipment->update([
                'waybill_id' => $waybillId,
                'raw_response' => $payload,
            ]);

            $shipment->histories()->create([
                'status' => $shipment->status,
                'note' => 'Waybill ID updated by Biteship',
                'raw_payload' => $payload,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
