<?php

namespace App\Http\Livewire\Backend\Report;

use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use App\Models\SemiCustomProduct;
use App\Models\Store;
use Livewire\Component;

class DashboardBreakdownBrandComponent extends Component
{
    public Brand $brand;
    public $orders;

    public $chartData = [
        'labels' => [],
        'series' => [],
        'chart' => [
            'width' => 400,
            'type' => 'bar',
        ],
    ];
    public $totalSelling = 0;

    public function mount($brand)
    {
        $this->brand = $brand->loadCount('products');

        $this->loadData();

        $this->getChartData();

        $this->dispatch('chartDataFilled');

    }

    public function render()
    {
        return view('livewire.backend.report.dashboard-breakdown-brand-component');
    }

    // Load orders (loadData)
    // if brands is karuizawa(id = 2). load orders with product type is SemiCustom && Type is Product on its brand
    // else show only orders where Type is Products on its brand
    public function loadData()
    {
        $brandId = $this->brand->id;

        $orders = Order::without('payments')->whereHas('orderItems', function ($query) use ($brandId) {
            $query->where(function ($q) use ($brandId) {
                // Ready to Wear products for this brand
                $q->whereHas('product_rtw', function ($productQuery) use ($brandId) {
                    $productQuery->where('brand_id', $brandId);
                });

                // If brand is Karuizawa (ID 2), also include Semi Custom products
                if ($brandId == 2) {
                    $q->orWhere('product_type', 'App\Models\SemiCustomProduct');
                }
            });

            return $query;
        })
        ->where('status', 'completed')
        ->where('created_at', '>=', now()->subDays(30))
        ->latest()
        ->get();

        $this->orders = $orders;
    }

    public function getChartData()
    {
        // make array date range start from 30 days ago
        $dateRange = [];
        $counter = 30;
        for ($i = 0; $i <= 30; $i++) {
            $dateRange[] = now()->subDays($counter)->format('M-d');
            $counter--;
        }



        $seriesData = [];
        $stores = Store::select('id', 'name')->get();

        foreach ($stores as $store) {
            $seriesData[$store->name] = array_fill_keys($dateRange, 0);
        }

        foreach ($this->orders as $order) {
            $date = $order->created_at->format('M-d');
            $storeName = $order->store->name;

            if (isset($seriesData[$storeName][$date])) {
                foreach ($order->orderItems as $item) {
                    // Logic to only sum items belonging to this brand or SC if brand is Karuizawa
                    if ($item->isReadyToWear() && $item->product_rtw->brand_id == $this->brand->id) {
                        $seriesData[$storeName][$date] += $item->total_price;
                    } elseif ($this->brand->id == 2 && $item->isSemiCustom()) {
                        $seriesData[$storeName][$date] += $item->price;
                    }
                }
            }
        }

        $series = [];
        foreach ($seriesData as $storeName => $data) {
            $series[] = [
                'name' => $storeName,
                'data' => array_values($data),
            ];
            $this->totalSelling += array_sum($data);
        }

        $this->chartData = [
            'labels' => $dateRange,
            'series' => $series,
            'chart' => [
                'height' => 350,
                'type' => 'bar',
                'stacked' => true,
            ],
            'xaxis' => [
                'categories' => $dateRange,
            ],
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
        ];


    }
}
