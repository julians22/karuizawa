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
            ->firstOrFail();

        if($order->isPaymentComplete()) {
            return redirect()->route('frontend.user.dashboard')->with('flash_success', 'Payment already completed');
        }

        return view('frontend.user.order-payment', compact('order'));
    }

    public function print_sc($id)
    {
        $semiCustom = SemiCustomProduct::findOrFail($id);
        $dataConfig = config('karuizawa-master');

        $semiCustom = $semiCustom->toArray();

        $semiCustom = $semiCustom;

        $newConfigCleric = [];

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

            if ($key == 'cleric'){

                $option_form = $semiCustom['option_form']["cleric"];


                if (array_key_exists('slug', $option_form)) {
                    $slugSelected = $option_form['slug'];
                    $optionClericSubData = $option_form['data'];
                    foreach ($dataConfig['cleric']['data']['options'] as $keyK => $configs){
                        foreach ($configs as $keyL => $config){
                            if ($config['slug'] == $slugSelected){
                                $dataConfig['cleric']['data']['options'][$keyK][$keyL]['selected'] = true;
                                $clericSubData = $dataConfig['cleric']['data']['options'][$keyK][$keyL]['data'];
                                foreach ($optionClericSubData as $keyM => $value){
                                    foreach ($clericSubData as $keyN => $config){
                                        if ($config['slug'] == $optionClericSubData[$keyM]['slug']){
                                            $dataConfig['cleric']['data']['options'][$keyK][$keyL]['data'][$keyN]['selected'] = true;
                                        }
                                    }
                                }
                            }
                        }

                        foreach ($configs as $keyL => $config){
                            if ($config['slug'] == $slugSelected){
                                if (array_key_exists('fabricCode', $semiCustom['option_form']["cleric"])){
                                    $dataConfig['cleric']['data']['fabric'][$keyK]['code'] = $semiCustom['option_form']["cleric"]['fabricCode'];
                                }else{
                                    $dataConfig['cleric']['data']['fabric'][$keyK]['code'] = [];

                                    dd('as');
                                }
                            }else{
                                $dataConfig['cleric']['data']['fabric'][$keyK]['code'] = [];
                            }

                        }
                    }
                }else{
                    foreach ($dataConfig['cleric']['data']['options'] as $keyK => $configs){
                        foreach ($configs as $keyL => $config){
                            $dataConfig['cleric']['data']['options'][$keyK][$keyL]['selected'] = false;
                        }

                        $dataConfig['cleric']['data']['fabric'][$keyK]['code'] = [];
                    }
                }
            }

        }
        $semiCustom = collect($semiCustom);

       return view('frontend.print.semi-custom', ['dataSemiCustom' => $semiCustom, 'dataConfig' => collect($dataConfig)]);


    }

    public function print_bill($id)
    {
        $order = Order::with(['orderItems.product', 'store', 'user', 'payments'])
        ->findOrFail($id);

        // dd($order);
        return view('frontend.print.bill', ['order' => $order]);
    }
}
