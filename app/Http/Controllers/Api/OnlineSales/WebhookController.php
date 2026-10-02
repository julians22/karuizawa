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
     * Handle Biteship order/status webhooks.
     * @see https://biteship.com/docs/webhook
     */
    public function biteship(Request $request)
    {
        $token = config('biteship.webhook_token');

        if ($token && $request->header('Authorization') !== $token) {
            Log::warning('Rejected Biteship webhook with invalid token');

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        $biteshipOrderId = $payload['order_id'] ?? $payload['id'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$biteshipOrderId || !$status) {
            return response()->json(['success' => false, 'message' => 'Invalid payload'], 422);
        }

        $shipment = OrderShipment::where('biteship_order_id', $biteshipOrderId)->first();

        if (!$shipment) {
            Log::warning('Biteship webhook for unknown order', ['order_id' => $biteshipOrderId]);

            return response()->json(['success' => false, 'message' => 'Shipment not found'], 404);
        }

        $shipment->update([
            'status' => $status,
            'waybill_id' => $payload['courier_waybill_id'] ?? $payload['waybill_id'] ?? $shipment->waybill_id,
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

        return response()->json(['success' => true]);
    }
}
