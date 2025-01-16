<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderHistoryResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
                'orderItems',
                'orderItems.product',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(
            OrderHistoryResource::collection($orders)
        );
    }

    function incoming_order(){
        $orders = Order::with([
                'orderItems',
                'orderItems.product',
            ])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(
            OrderHistoryResource::collection($orders)
        );
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')
            ->findOrFail($id);

        return response()->json($order);
    }


    public function storeSemiCustom(Request $request){

        return response()->json([
            'success' => true,
            'data' => $request->all(),
        ], 200);
    }

    function store(Request $request) {

        // use error

        $request->validate([
            'products.*.sku' => 'required',
            'products.*.qty' => 'required',
            'payment' => 'required',
            'bank' => 'required_if:payment,manual-tf',
            'customer_id' => 'sometimes',
        ]);

        // check customer_id
        if ($request->customer_id) {
            $customer = Customer::find($request->customer_id);
            if (!$customer) {
                // return error validation
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                ], 422);
            }
        }


        DB::beginTransaction();

        try {
            $order = Order::create([
                'customer_id' => $request->customer_id ?? config('dummy.customer'),
                'store_id' => config('dummy.store'),
                'user_id' => 3,
                'total_price' => 0,
                'payment' => $request->bank ?? null,
                'bank' => $request->bank ?? null,
                'status' => config('enums.order_status.pending'),
            ]);

            foreach ($request->products as $product) {
                $productdb = Product::sku($product['sku'])->first();
                $orderItem = new OrderItem([
                    'quantity' => $product['qty'],
                    'price' => $productdb->price,
                ]);

                $orderItem->product()->associate($productdb);
                $order->orderItems()->save($orderItem);
            }

            $totalPrice = $order->orderItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            $order->update(['total_price' => $totalPrice]);

            // TODO store to accurate order number

            $completionPayment = Payment::create([
                'order_id' => $order->id,
                'user_id' => 3,
                'amount' => $totalPrice,
                'is_downpayment' => false,
            ]);

            $order->update(['status' => 'completed']);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $order,
        ], 200);

    }

}
