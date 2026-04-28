<?php

namespace App\Http\Controllers\Frontend\User;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\SemiCustomOuterProduct;
use Illuminate\Http\Request;
use App\Models\SemiCustomProduct;

/**
 * Class DashboardController.
 */
class DashboardController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('frontend.user.dashboard');
    }

    public function payment($order_uuid)
    {
        $order = Order::where('uuid', $order_uuid)
            ->with('orderItems', 'orderItems.product')
            ->firstOrFail();

        if($order->isPaymentComplete()) {
            return redirect()->route('frontend.user.dashboard')->with('flash_success', 'Payment already completed');
        }

        return view('frontend.user.order-payment', compact('order'));
    }

    public function print_sc($id)
    {
        $semiCustom = SemiCustomProduct::with([
                'customer',
                'orderItem',
                'orderItem.order.user' => function ($query) {
                    $query->withTrashed();
                }
            ])->findOrFail($id);
        $dataConfig = config('karuizawa-default-master');

        $semiCustom = $semiCustom->toArray();

       return view('frontend.print.semi-custom', ['dataSemiCustom' => $semiCustom, 'dataConfig' => collect($dataConfig), 'type' => 'semi-custom']);
    }

    public function print_sc_outer($id)
    {
        $semiCustomOuter = SemiCustomOuterProduct::with([
                'customer',
                'orderItem',
                'orderItem.order.user' => function ($query) {
                    $query->withTrashed();
                }
            ])->findOrFail($id);
        $dataConfig = config('karuizawa-outer-shirt-master');
        $semiCustomOuter = $semiCustomOuter->toArray();
        return view('frontend.print.semi-custom', ['dataSemiCustom' => $semiCustomOuter, 'dataConfig' => collect($dataConfig), 'type' => 'semi-custom-outer']);
    }

    public function print_bill($id)
    {
        $order = Order::with(['orderItems.product', 'store', 'user', 'payments'])
        ->findOrFail($id);

        return view('frontend.print.bill', ['order' => $order]);
    }

    public function print_sc_per_day(Request $request)
    {
        $totalAllSemiCustom = 0;
        $storeId = auth()->user()->store_id ?? null;
        $semiCustom = SemiCustomProduct::with([
            'customer',
            'orderItem',
            'orderItem.order.store' => function ($query) use ($storeId) {
                return $query->where('id', $storeId);
            },
            'orderItem.order.user' => function ($query) {
                $query->withTrashed();
            }
        ])->whereDate('created_at', '=', $request->date)->orderBy('created_at', 'desc')->get();
        $dataConfig = config('karuizawa-default-master');

        $totalAllSemiCustom += $semiCustom->count();


        $semiCustomOuter = SemiCustomOuterProduct::with([
            'customer',
            'orderItem',
            'orderItem.order.store' => function ($query) use ($storeId) {
                $query->where('id', $storeId);
            },
            'orderItem.order.user' => function ($query) {
                $query->withTrashed();
            }
        ])->whereDate('created_at', '=', $request->date)->orderBy('created_at', 'desc')->get();
        $dataConfigOuter = config('karuizawa-outer-shirt-master');
        $totalAllSemiCustom += $semiCustomOuter->count();

        return view('frontend.print.sc-per-day', [
            'dataSemiCustom' => $semiCustom,
            'dataConfig' => collect($dataConfig),
            'dataSemiCustomOuter' => $semiCustomOuter,
            'dataConfigOuter' => collect($dataConfigOuter),
            'totalAllSemiCustom' => $totalAllSemiCustom,
            'date' => $request->date]);
    }
}
