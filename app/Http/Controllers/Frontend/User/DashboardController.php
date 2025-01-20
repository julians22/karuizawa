<?php

namespace App\Http\Controllers\Frontend\User;

use App\Models\Order;
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
            ->first();

        if($order->isPaymentComplete()) {
            return redirect()->route('frontend.user.dashboard')->with('flash_success', 'Payment already completed');
        }

        return view('frontend.user.order-payment', compact('order'));
    }

    function print_sc($id)
    {
        // $order = Order::findOrFail($id);
        // $orderItem = $order->orderItems()->where('product_type', 'App\Models\SemiCustomProduct')->first();
        $semiCustom = SemiCustomProduct::findOrFail($id);
        $dataConfig = collect(config('karuizawa-master'));

        $semiCustom = $semiCustom->toArray();

        foreach ($dataConfig as $key => $value) {

            if ($key == 'embroidery') {

                $option_form = $semiCustom['option_form']["embroidery"];

                if (!array_key_exists('initialName', $semiCustom['option_form']['embroidery'])) {
                    $semiCustom['option_form']['embroidery']['initialName'] = [];
                    foreach ($option_form as $key => $value) {
                        $semiCustom['option_form']['embroidery']['initialName'][$key] = '';
                    }
                }
            }

            // if ($key == 'cleric'){

            //     $option_form = $semiCustom['option_form']["cleric"];

            //     $newConfigCleric = [];

            //     if (array_key_exists('slug', $option_form)) {
            //         foreach ($dataConfig['cleric']['data']['options'] as $config){

            //             foreach ($config as $key2 => $value2) {

            //                 if ($value2['slug'] == $option_form['slug']) {

            //                 }
            //             }

            //         }

            //     }
            // }

        }

        $semiCustom = collect($semiCustom);

       return view('frontend.print.semi-custom', ['dataSemiCustom' => $semiCustom, 'dataConfig' => $dataConfig]);


    }
}
