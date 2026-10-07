<?php

namespace App\Http\Controllers\Api\OnlineSales;

use App\Http\Controllers\Controller;
use App\Jobs\CreateBiteshipShipmentJob;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Confirm a Midtrans payment for an online order, relayed by the Kizoku
     * frontend's Midtrans webhook. Records the Payment, transitions the order,
     * and queues the Biteship shipment only after payment success.
     */
    public function confirm(Request $request)
    {
        $token = config('services.online_sales.payment_token');

        if (! $token || $request->header('Authorization') !== $token) {
            Log::warning('Rejected online-sales payment confirmation with invalid token');

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'midtrans_order_id' => 'required|string|max:100',
            'transaction_status' => 'required|string|max:50',
            'payment_type' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'gross_amount' => 'nullable|numeric|min:0',
            'fraud_status' => 'nullable|string|max:50',
        ]);

        $order = Order::where('midtrans_order_id', $validated['midtrans_order_id'])->first();

        if (! $order) {
            Log::warning('Payment confirmation for unknown Midtrans order', $validated);

            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $transactionStatus = $validated['transaction_status'];
        $fraudStatus = $validated['fraud_status'] ?? null;

        $isSuccess = $transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && $fraudStatus === 'accept');

        if ($isSuccess) {
            $this->handleSuccessfulPayment($order, $validated);

            return response()->json(['success' => true, 'message' => 'Payment recorded']);
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            if (! $order->isCancelled() && ! $order->isCompleted()) {
                $order->update(['status' => config('enums.order_status.cancelled')]);
            }

            return response()->json(['success' => true, 'message' => 'Order cancelled']);
        }

        // 'pending' (and anything else): order stays unpaid, no action needed.
        return response()->json(['success' => true, 'message' => 'Payment still pending']);
    }

    private function handleSuccessfulPayment(Order $order, array $data): void
    {
        $transactionId = (string) ($data['transaction_id'] ?? $data['midtrans_order_id']);

        // Idempotency: Midtrans may deliver the same notification more than once.
        if ($order->payments()->where('trans_number', $transactionId)->exists()) {
            return;
        }

        DB::transaction(function () use ($order, $data, $transactionId): void {
            Payment::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'payment' => 'midtrans-' . ($data['payment_type'] ?? 'snap'),
                'bank' => null,
                'amount' => (float) ($data['gross_amount'] ?? $order->total_price),
                'is_downpayment' => false,
                'trans_number' => $transactionId,
            ]);

            if (! $order->isCancelled() && ! $order->isCompleted()) {
                $order->update([
                    'payment' => 'midtrans',
                    'status' => config('enums.order_status.processing'),
                ]);
            }
        });

        // Payment settled: create the Biteship shipment for this order now.
        if ($order->shipment && ! $order->shipment->biteship_order_id) {
            CreateBiteshipShipmentJob::dispatch($order->shipment->id);
        }
    }
}
