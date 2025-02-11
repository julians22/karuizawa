<?php

namespace App\Http\Controllers\Frontend\User;

use Carbon\Carbon;
use App\Models\Order;
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
        $semiCustom = SemiCustomProduct::with(['customer', 'orderItem', 'orderItem.order.user'])->findOrFail($id);
        $dataConfig = config('karuizawa-master');

        $semiCustom = $semiCustom->toArray();

       return view('frontend.print.semi-custom', ['dataSemiCustom' => $semiCustom, 'dataConfig' => collect($dataConfig)]);


    }

    public function print_bill($id)
    {
        $order = Order::with(['orderItems.product', 'store', 'user', 'payments'])
        ->findOrFail($id);

        // dd($order);
        return view('frontend.print.bill', ['order' => $order]);
    }

    public function print_sc_per_day(Request $request)
    {
        $semiCustom = SemiCustomProduct::with(['customer', 'orderItem', 'orderItem.order.user'])->whereDate('created_at', '=', $request->date)->orderBy('created_at', 'desc')->get();
        $dataConfig = config('karuizawa-master');

        return view('frontend.print.sc-per-day', ['dataSemiCustom' => $semiCustom, 'dataConfig' => collect($dataConfig), 'date' => $request->date]);
    }
}
