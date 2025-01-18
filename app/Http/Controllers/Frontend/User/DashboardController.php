<?php

namespace App\Http\Controllers\Frontend\User;

use App\Models\Order;

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
            ->first();

        if($order->isPaymentComplete()) {
            return redirect()->route('frontend.user.dashboard')->with('flash_success', 'Payment already completed');
        }

        return view('frontend.user.order-payment', compact('order'));
    }
}
