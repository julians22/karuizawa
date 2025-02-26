<?php

namespace App\Http\Controllers\Backend;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;
use DB;

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

        /** @var Customer $customersQuery Get total customer and new customer in last 30 days */
        $customersQuery = Customer::select('id', 'created_at');

        /** @var int $totalCustomer Get total customer*/
        $totalCustomer = cache()->remember('totalCustomer', 60 * 60, function () use ($customersQuery) {
            return $customersQuery->count();
        });

        /** @var int $newCustomer Get new customer in last 30 days */
        $newCustomer = cache()->remember('newCustomer', 60 * 60, function () use ($customersQuery) {
            return $customersQuery->where('created_at', '>=', now()->subDays(30))->count();
        });

        /** @var Order $ordersQuery Get total price of orders today and last 30 days */
        Order::$withoutAppends = true;
        $ordersQuery = Order::select('id', 'store_id', 'created_at', 'total_price')
            ->without('payments')
            ->where('status', 'completed');

        /** @var float $totalTodayPrice Get total price of orders today */
        $totalTodayPrice = cache()->remember('totalTodayPrice', 60 * 60, function () use ($ordersQuery) {
            return with(clone $ordersQuery)->where('created_at', '>=', today())->sum('total_price');
        });

        /** @var float $totalLast30DaysPrice Get total price of orders last 30 days */
        $totalLast30DaysPrice = cache()->remember('totalLast30DaysPrice', 60 * 60, function () use ($ordersQuery) {
            return with(clone $ordersQuery)->where('created_at', '>=', today()->subDays(30))->sum('total_price');
        });


        $sellingByStore = cache()->remember('sellingByStore', 60 * 60, function () {
            return $this->getSellingByStore();
        });

        // match data to apexchart options
        $sellingByStoreData = [
            'labels' => [],
            'series' => [],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '55%',
                    'borderRadius' => 5,
                    'borderRadiusApplication' => 'end',

                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'xaxis' => [
                'categories' => $sellingByStore[array_key_first($sellingByStore)] ? array_keys($sellingByStore[array_key_first($sellingByStore)]) : [],
            ],
            'yaxis' => [
                'show' => false,
            ],
            'chart' => [
                'height' => 350,
                'type' => 'bar'
            ],
        ];

        foreach ($sellingByStore as $storeName => $storeData) {
            $sellingByStoreData['labels'][] = $storeName;
            $sellingByStoreData['series'][] = [
                'name' => $storeName,
                'data' => array_values($storeData),
            ];
        }



        return view('backend.dashboard')->with([
                'totalCustomer' => $totalCustomer,
                'newCustomer' => $newCustomer,
                'totalTodayPrice' => $totalTodayPrice,
                'totalLast30DaysPrice' => $totalLast30DaysPrice,
                'sellingByStoreData' => $sellingByStoreData,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function report()
    {
        return view('backend.report.index');
    }

    /**
     * Get selling by store
     *
     * @return array
     */
    private function getSellingByStore()
    {
        // make array date range start from 30 days ago
        $dateRange = [];
        $counter = 30;
        for ($i = 0; $i <= 30; $i++) {
            $dateRange[] = now()->subDays($counter)->format('M-d');
            $counter--;
        }



        $store = Store::select('id', 'name')
            ->with(['orders' => function ($query) {
                Order::$withoutAppends = true;
                $query->select('id', 'store_id', 'total_price', 'created_at')
                    ->without('payments')
                    ->where('status', 'completed')
                    ->whereRaw('created_at >= CURDATE() - INTERVAL 30 DAY');
            }]);

        $stores = $store->get();

        $sellingByStore = [];

        foreach ($stores as $store) {
            $sellingByStore[$store->name] = [];
            foreach ($dateRange as $date) {
                $sellingByStore[$store->name][$date] = 0;
            }

            foreach ($store->orders as $order) {
                $sellingByStore[$store->name][$order->created_at->format('M-d')] += $order->total_price;
            }
        }

        return $sellingByStore;
    }
}
