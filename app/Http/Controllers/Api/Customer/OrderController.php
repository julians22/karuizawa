<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List the authenticated customer's online orders, newest first.
     */
    public function index(Request $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $orders = Order::where('customer_id', $customer->id)
            ->with([
                'orderItems.product:id,product_name,sku',
                'shipment:id,order_id,courier_name,courier_service_name,status,waybill_id,biteship_tracking_id',
            ])
            ->latest('id')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => collect($orders->items())->map(fn (Order $order) => $this->present($order))->values(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    private function present(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'total_price' => (float) $order->total_price,
            'order_date' => $order->order_date,
            'created_at' => $order->created_at,
            'items' => $order->orderItems->map(fn ($item) => [
                'name' => $item->product->product_name ?? 'Item',
                'qty' => (int) $item->quantity,
                'price' => (float) $item->price,
            ])->values(),
            'shipment' => $order->shipment ? [
                'courier_name' => $order->shipment->courier_name,
                'courier_service_name' => $order->shipment->courier_service_name,
                'status' => $order->shipment->status,
                'waybill_id' => $order->shipment->waybill_id,
                'tracking_id' => $order->shipment->biteship_tracking_id,
            ] : null,
        ];
    }
}
