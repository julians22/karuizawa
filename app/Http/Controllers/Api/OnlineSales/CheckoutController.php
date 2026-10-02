<?php

namespace App\Http\Controllers\Api\OnlineSales;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnlineSales\CheckoutRequest;
use App\Jobs\CreateBiteshipShipmentJob;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShipment;
use App\Models\Product;
use App\Models\ProductActualStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Create a new online order: persists customer, shipping address, order items
     * and the selected courier, then queues the Biteship shipment creation.
     */
    public function store(CheckoutRequest $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();

        DB::beginTransaction();

        try {
            $address = $this->resolveAddress($request, $customer);

            $storeId = $request->input('store_id') ?? config('dummy.store');

            $order = Order::create([
                'customer_id' => $customer->id,
                'store_id' => $storeId,
                'user_id' => config('dummy.user'),
                'total_price' => 0,
                'discount_details' => null,
                'payment' => null,
                'bank' => null,
                'status' => config('enums.order_status.pending'),
                'source' => config('enums.order_source.online'),
                'order_date' => now(),
            ]);

            $order->update([
                'order_number' => 'ONL-' . $order->store->code . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            ]);

            $totalPrice = 0;

            foreach ($request->input('items') as $item) {
                $product = Product::where('sku', $item['sku'])->firstOrFail();

                $stock = ProductActualStock::where('product_id', $product->id)
                    ->where('store_id', $storeId)
                    ->lockForUpdate()
                    ->first();

                if (!$stock || $stock->stock_quantity < $item['qty']) {
                    throw new \RuntimeException("Insufficient stock for product {$product->sku}");
                }

                $stock->stock_quantity -= $item['qty'];
                $stock->save();

                $orderItem = new OrderItem([
                    'quantity' => $item['qty'],
                    'price' => $product->price,
                    'discount' => 0,
                    'discount_detail' => ['discount' => 0, 'discount_amount' => 0],
                ]);

                $orderItem->product()->associate($product);
                $order->orderItems()->save($orderItem);

                $totalPrice += $product->price * $item['qty'];
            }

            $shippingCost = (float) $request->input('courier.shipping_cost', 0);
            $insuranceCost = (float) $request->input('courier.insurance_cost', 0);

            $order->update([
                'total_price' => $totalPrice + $shippingCost + $insuranceCost,
            ]);

            $shipment = OrderShipment::create([
                'order_id' => $order->id,
                'customer_address_id' => $address?->id,
                'recipient_name' => $request->input('address.recipient_name'),
                'recipient_phone' => $request->input('address.recipient_phone'),
                'recipient_email' => $request->input('address.recipient_email') ?? $customer->email,
                'recipient_address' => $request->input('address.full_address'),
                'area_id' => $request->input('address.area_id'),
                'area_name' => $request->input('address.area_name'),
                'postal_code' => $request->input('address.postal_code'),
                'latitude' => $request->input('address.latitude'),
                'longitude' => $request->input('address.longitude'),
                'courier_company' => $request->input('courier.company'),
                'courier_type' => $request->input('courier.type'),
                'courier_name' => $request->input('courier.name'),
                'courier_service_name' => $request->input('courier.service_name'),
                'shipping_cost' => $shippingCost,
                'insurance_cost' => $insuranceCost,
                'status' => OrderShipment::STATUS_PENDING,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Online checkout failed: ' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 422);
        }

        CreateBiteshipShipmentJob::dispatch($shipment->id);

        $order->load('orderItems.product', 'customer', 'shipment');

        return response()->json([
            'success' => true,
            'message' => 'Order created',
            'data' => $order,
        ], 201);
    }

    private function resolveAddress(CheckoutRequest $request, Customer $customer): ?CustomerAddress
    {
        if ($request->filled('address.customer_address_id')) {
            // Only allow addresses owned by the authenticated customer.
            return CustomerAddress::where('customer_id', $customer->id)
                ->findOrFail($request->input('address.customer_address_id'));
        }

        if (!$request->boolean('address.save_address')) {
            return null;
        }

        return CustomerAddress::create([
            'customer_id' => $customer->id,
            'label' => $request->input('address.label') ?? 'Online Order',
            'recipient_name' => $request->input('address.recipient_name'),
            'recipient_phone' => $request->input('address.recipient_phone'),
            'recipient_email' => $request->input('address.recipient_email') ?? $customer->email,
            'full_address' => $request->input('address.full_address'),
            'note' => $request->input('address.note'),
            'area_id' => $request->input('address.area_id'),
            'area_name' => $request->input('address.area_name'),
            'postal_code' => $request->input('address.postal_code'),
            'latitude' => $request->input('address.latitude'),
            'longitude' => $request->input('address.longitude'),
            'is_primary' => false,
        ]);
    }
}
