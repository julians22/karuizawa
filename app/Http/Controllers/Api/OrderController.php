<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderHistoryResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductActualStock;
use App\Models\SemiCustomProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with([
                'orderItems',
                'orderItems.product',
            ])
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->date, function ($query) use ($request) {
                $date = Carbon::parse($request->date);
                return $query->whereDate('created_at', $date);
            })
            ->when($request->keyword, function ($query) use ($request) {
                // we can search through customer name, email, phone
                return $query->where('id', 'like', "%$request->keyword%")
                    ->orWhereHas('customer', function ($query) use ($request) {
                        $query->where('full_name', 'like', "%$request->keyword%")
                            ->orWhere('email', 'like', "%$request->keyword%")
                            ->orWhere('phone', 'like', "%$request->keyword%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(
            OrderHistoryResource::collection($orders)
        );
    }

    // this function not used yet
    public function store_payment(Request $request) {
        $request->validate([
            'products.*.sku' => 'required',
            'products.*.qty' => 'required',
            'payment' => 'required',
            'bank' => 'required_if:payment,manual-tf',
            'customer_id' => 'sometimes',
            'coupon' => 'sometimes',
        ]);
    }

    function incoming_order(Request $request) {
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

        $request->validate([
            'products.*.sku' => 'required',
            'products.*.qty' => 'required',
            'customer_id' => 'sometimes|required_if:semi_custom,!=,null',
            'coupon' => 'sometimes',
            'semi_custom' => 'sometimes',
            'user' => 'sometimes',
        ]);

        // check customer_id
        $storeId = $request->store_id;
        if ($request->customer_id) {
            $customer = Customer::find($request->customer_id);
            if (!$customer) {
                // return error validation
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                ], 422);
            }

            if ($customer->store_id) {
                $storeId = $customer->store_id;
            }
        }

        $storeId = $storeId == 0 ? 1 : $storeId;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'customer_id' => $request->customer_id ?? config('dummy.customer'),
                'store_id' => $storeId,
                'user_id' => $request->user ?? 3,
                'total_price' => 0,
                'discount_details' => [
                    'coupon' => (int) $request->coupon ?? null,
                    'discount_amount' => 0,
                ],
                'payment' => null,
                'bank' => null,
                'status' => config('enums.order_status.pending'),
            ]);

            // insert order items type product
            foreach ($request->products as $product) {
                $productdb = Product::sku($product['sku'])->first();
                $orderItem = new OrderItem([
                    'quantity' => $product['qty'],
                    'price' => $productdb->price,
                ]);

                $stock = ProductActualStock::where('product_id', $productdb->id)
                    ->where('store_id', $storeId)
                    ->lockForUpdate()
                    ->first();

                if ($stock->stock_quantity < $product['qty']) {
                    Log::info('Stock not enough on store ' . $storeId . ' for product ' . $product['sku'] . ' with qty ' . $product['qty'] . ' current stock ' . $stock->stock_quantity);
                }

                $stock->stock_quantity -= $product['qty'];
                $stock->save();

                $orderItem->product()->associate($productdb);
                $order->orderItems()->save($orderItem);
            }


            // insert order items type semi custom
            if ($request->semi_custom) {
                $semiCustom = $request->semi_custom;

                $customer = Customer::findOrFail($request->customer);

                foreach ($semiCustom as $sc) {
                    $semiCustomProuduct = SemiCustomProduct::create([
                        'name' => 'Semi Custom MTM' . "(" . $customer->full_name . ")",
                        'code' => config('enums.semi_custom_name'),
                        'customer_id' => $customer->id,
                        'basic_form' => $sc['basic_form'],
                        'base_price' => $sc['base_price'],
                        'base_discount' => $sc['base_discount'],
                        'option_form' => $sc['option_form'],
                        'option_total' => $sc['option_total'],
                        'option_additional_price' => $sc['option_additional_price'],
                        'option_discount' => $sc['option_discount'],
                        'size' => $sc['size'],
                        'base_note' => $sc['base_note'],
                        'option_note' => $sc['option_note'],
                    ]);

                    $orderItem = new OrderItem([
                        'quantity' => 1,
                        'price' => $semiCustomProuduct->base_price + $semiCustomProuduct->option_total + $semiCustomProuduct->option_additional_price,
                    ]);

                    $orderItem->product()->associate($semiCustomProuduct);
                    $order->orderItems()->save($orderItem);
                }


            }

            $totalPrice = 0;

            foreach ($order->orderItems as $item) {

                if ($item->isReadyToWear()) {
                    $totalPrice += $item->price * $item->quantity;
                }

                if ($item->isSemiCustom()) {
                    $calucate = $item->product->base_price + $item->product->option_total + $item->product->option_additional_price;
                    $totalPrice += $calucate;
                }
            }

            $orderCoupon = $order->discount_details['coupon'] ?? 0;

            $discountAmount = 0;
            if ($orderCoupon) {
                $discountAmount = $totalPrice * $orderCoupon / 100;
            }

            $totalPrice -= $discountAmount;

            $order->update([
                'total_price' => $totalPrice,
                'discount_details' => [
                    'coupon' => $request->coupon ?? null,
                    'discount_amount' => $discountAmount,
                ],
                'discount' => $discountAmount,
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
        $lastOrderByStore = Order::where('store_id', $order->store_id)->latest()->first();

        $store = $order->store->code;
        $order_number = $store . '-' . str_pad($lastOrderByStore->id + 1, 5, '0', STR_PAD_LEFT);

        $order->update([
            'order_number' => $order_number,
        ]);

        DB::commit();
        return response()->json([
            'success' => true,
            'data' => $order,
            'redirect' => route('frontend.user.order-payment', $order->uuid),
        ], 200);

    }

}
