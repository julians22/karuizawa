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

        $detailItem = [];
        foreach ($orderItems as $orderItem) {
            if ($orderItem->isReadyToWear())
            {
                $detailItem[] = [
                    'itemNo' => $orderItem->product->sku,
                    'unitPrice' => $orderItem->price,
                    'detailName' => $orderItem->product->name,
                    'quantity' => $orderItem->quantity,
                    'warehouseName' => $order->store->accurate_alias,
                    'departmentName' => 'Apparel',
                ];
            }
            else if($orderItem->isSemiCustom())
            {
                $detailItem[] = [
                    'itemNo' => config('accurate.semi_custom_sku'),
                    'unitPrice' => $orderItem->price,
                    'detailName' => $orderItem->product->name,
                    'quantity' => $orderItem->quantity,
                    'warehouseName' => $order->store->accurate_alias,
                    'departmentName' => 'Apparel',
                ];
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
            'typeAutoNumber' => 55
        ];

        // pre $data
        echo "<pre>";
        print_r(json_encode($data, JSON_PRETTY_PRINT));
        echo "</pre>";

        die();

        try {

            $cachedAuth = cache()->get('accurate_token');
            $cachedDb = cache()->get('accurate_db');
            $appUrl = config('accurate.auth.app_url');

            $headers = [
                'Authorization' => 'Bearer ' . $cachedAuth['access_token'],
                'X-Session-ID' => $cachedDb['session'],
            ];

            $response = Http::withHeaders($headers)
                ->post($appUrl . '/accurate/api/sales-order/save.do', $data);

            dd($response);

            if ($response->status() == 200) {
                $response = $response->json();
                dd($response->json());
                $order->accurate_order_id = $response['r']['id'];
                $order->accurate_order_number = $response['r']['number'];

                $order->save();
            }else{
                dd($response);
                return redirect()->route('admin.order.show', $order)->withFlashDanger(__('An error occurred while uploading the order.'));
            }

        } catch (\Throwable $th) {
            Log::error($th);

            return redirect()->route('admin.order.show', $order)->withFlashDanger(__('An error occurred while uploading the order.'));
        }

        return redirect()->route('admin.order.show', $order)->withFlashSuccess(__('The order was successfully uploaded.'));
    }


    // {
    //     "customerNo": "C.00001",
    //     "detailDownPayment": [
    //       {
    //         "invoiceNumber": "2025.01.0001",
    //         "paymentAmount": 500000
    //       }
    //     ],
    //     "detailItem": [
    //       {
    //         "itemNo": "D08",
    //         "unitPrice": 500000,
    //         "detailName": "Dasi BLACK D08",
    //         "detailNotes": "Halo 123",
    //         "quantity": 2
    //       },
    //       {
    //         "itemNo": "AJST95COL02M",
    //         "unitPrice": 1500000,
    //         "detailName": "Light Jacket Karuizawa Shirt AJST95 col 02 M",
    //         "detailNotes": "Halo 123",
    //         "quantity": 2
    //       }
    //     ],
    //     "transDate": "14/01/2025",
    //     "currencyCode": "IDR",
    //     "description": "string",
    //     "documentCode": "DIGUNGGUNG",
    //     "taxable": true,
    //     "inclusiveTax": true,
    //     "typeAutoNumber": 56
    //   }
}
