<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Http;
use Illuminate\Http\Request;
use Log;

class OrderController extends Controller
{

    public function index()
    {
        return view('backend.order.index');
    }

    public function show(Order $order)
    {
        return view('backend.order.show', compact('order'));
    }

    function uploadAccurate(Order $order)
    {
        $order->load('orderItems.product', 'store', 'customer');
        $orderItems = $order->orderItems;

        $customerNo = config('accurate.customer_list.AST');
        if ($order->store->code !== null) {
            $customerNo = config('accurate.customer_list.' . $order->store->code);
        }

        $warehouseName = config('accurate.warehouse_list.AST');
        if ($order->store->code !== null) {
            $warehouseName = config('accurate.warehouse_list.' . $order->store->code);
        }

        $detailItem = [];
        foreach ($orderItems as $orderItem) {
            if ($orderItem->isReadyToWear())
            {
                $detailItem[] = [
                    'itemNo' => $orderItem->product->sku,
                    'unitPrice' => $orderItem->price,
                    'detailName' => $orderItem->product->product_name,
                    'quantity' => $orderItem->quantity,
                    'departmentName' => 'Apparel',
                    'warehouseName' => $warehouseName,
                ];
            }
            else if($orderItem->isSemiCustom())
            {
                $detailItem[] = [
                    'itemNo' => config('accurate.semi_custom_sku'),
                    'unitPrice' => $orderItem->price,
                    'detailName' => $orderItem->product->name,
                    'quantity' => $orderItem->quantity,
                    'departmentName' => 'Apparel',
                    'warehouseName' => $warehouseName
                ];
            }
        }

        $autoNumber = 55;

        if ($order->store->code !== null) {
            if ($order->store->code == 'PIK') {
                $autoNumber = config('accurate.trans_no.PIK');
            }

            if ($order->store->code == 'AST') {
                $autoNumber = config('accurate.trans_no.AST');
            }
        }


        $data = [
            'customerNo' => $customerNo,
            'detailItem' => $detailItem,
            'transDate' => $order->order_date ? $order->order_date->format('d/m/Y') : $order->created_at->format('d/m/Y'),
            'currencyCode' => 'IDR',
            'description' => 'Web Order ' . $order->order_number,
            'documentCode' => 'DIGUNGGUNG',
            'taxable' => true,
            'inclusiveTax' => true,
            'typeAutoNumber' => $autoNumber,
        ];

        try {

            $cachedAuth = cache()->get('accurate_token');
            $cachedDb = cache()->get('accurate_db');
            $appUrl = config('accurate.auth.app_url');

            $headers = [
                'Authorization' => 'Bearer ' . $cachedAuth['access_token'],
                'X-Session-ID' => $cachedDb['session'],
            ];

            $response = Http::withHeaders($headers)
                ->post($appUrl . '/accurate/api/sales-invoice/save.do', $data);

            if ($response->status() == 200) {
                $response = $response->json();
                $order->accurate_order_id = $response['r']['id'];
                $order->accurate_order_number = $response['r']['number'];
                $order->accurate_sync_date = now();

                $order->save();
            }else{
                return redirect()->route('admin.order.show', $order)->withFlashDanger(__('An error occurred while uploading the order.'));
            }

        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('admin.order.show', $order)->withFlashDanger(__('An error occurred while uploading the order.'));
        }

        return redirect()->route('admin.order.show', $order)->withFlashSuccess(__('The order was successfully uploaded.'));
    }

    private function setUpPayment($invoiceNumber, $paymentAmount)
    {
        return [
            'invoiceNumber' => $invoiceNumber,
            'paymentAmount' => $paymentAmount
        ];

    }

}
