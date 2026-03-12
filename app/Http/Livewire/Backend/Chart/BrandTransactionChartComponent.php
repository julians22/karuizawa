<?php

namespace App\Http\Livewire\Backend\Chart;

use App\Models\Brand;
use App\Models\Order;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BrandTransactionChartComponent extends Component
{
    #[Reactive]
    public $month = ""; // month in format Y-m

    #[Reactive]
    public $daily = null;

    public Brand $brand;

    public $chartData = [];
    public $totalSelling = 0;

    public function mount($brand)
    {
        $this->brand = $brand;
        $this->generateReportData();
        $this->dispatch('chartDataFilled');
    }

    public function render()
    {
        return view('livewire.backend.chart.brand-transaction-chart-component');
    }

    public function generateReportData()
    {
        $this->chartData = [];
        $this->totalSelling = 0;

        // load data for donut chart seperated by store
        $brandId = $this->brand->id;

        $query = Order::query()
            ->where('status', 'completed')
            ->whereHas('orderItems', function ($query) use ($brandId) {
                $query->where(function ($q) use ($brandId) {
                    $q->whereHas('product_rtw', function ($productQuery) use ($brandId) {
                        $productQuery->where('brand_id', $brandId);
                    });

                    if ($brandId == 2) {
                        $q->orWhere('product_type', 'App\Models\SemiCustomProduct');
                    }
                });
            });

        if ($this->daily) {
            $query->whereDate('created_at', $this->daily);
        } elseif ($this->month) {
            $query->whereMonth('created_at', date('m', strtotime($this->month)))
                  ->whereYear('created_at', date('Y', strtotime($this->month)));
        } else {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        $orders = $query->with(['orderItems.product_rtw', 'store'])->get();

        $storeData = [];
        foreach ($orders as $order) {
            $storeName = $order->store->name;
            if (!isset($storeData[$storeName])) {
                $storeData[$storeName] = 0;
            }

            foreach ($order->orderItems as $item) {
                if ($item->isReadyToWear() && $item->product_rtw->brand_id == $brandId) {
                    $storeData[$storeName] += $item->total_price;
                } elseif ($brandId == 2 && $item->isSemiCustom()) {
                    $storeData[$storeName] += $item->price;
                }
            }
        }

        $this->totalSelling = array_sum($storeData);

        $this->chartData = [
            'series' => array_values($storeData),
            'labels' => array_keys($storeData),
            'chart' => [
                'type' => 'donut',
                'width' => 400,
            ],
            'responsive' => [
                [
                    'breakpoint' => 480,
                    'options' => [
                        'chart' => [
                            'width' => 200
                        ],
                        'legend' => [
                            'position' => 'bottom'
                        ]
                    ]
                ]
            ]
        ];

    }

    #[On('updateChart')]
    public function updateChart($month = null, $daily = null)
    {
        $this->month = $month;
        $this->daily = $daily;
        $this->generateReportData();
        $this->dispatch('chartDataFilled');
    }
}
