<?php

namespace App\Http\Livewire\Backend\Chart;

use App\Models\Order;
use App\Models\Store;
use Carbon\Carbon;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class StoreTransactionChartComponent extends Component
{
    #[Reactive]
    public $month = ""; // month in format Y-m

    public $chartData = [];
    public $totalSelling = 0;

    public function render()
    {
        return view('livewire.backend.chart.store-transaction-chart-component');
    }

    public function mount()
    {
        $this->generateReportData();
        $this->dispatch('chartDataFilled');
    }

    public function validateReactiveProp()
    {
        $this->generateReportData();
        $this->dispatch('chartDataFilled');
    }

    public function generateReportData()
    {

        $this->chartData = [];
        $this->totalSelling = 0;

        // {
        //     series: [44, 55, 13, 43, 22],
        //     chart: {
        //     width: 380,
        //     type: 'pie',
        //   },
        //   labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
        //   responsive: [{
        //     breakpoint: 480,
        //     options: {
        //       chart: {
        //         width: 200
        //       },
        //       legend: {
        //         position: 'bottom'
        //       }
        //     }
        //   }]
        // };

        $store = Store::select('id', 'name')
            ->with(['orders' => function ($query) {
                Order::$withoutAppends = true;
                $query->select('id', 'store_id', 'total_price', 'created_at')
                    ->without('payments')
                    ->where('status', 'completed')
                    ->whereMonth('order_date', Carbon::parse($this->month)->format('m'))
                    ->whereYear('order_date', Carbon::parse($this->month)->format('Y'));
            }])
            ->get();

        $labels = [];
        $series = [];

        foreach ($store as $store) {
            $labels[] = $store->name;
            $series[] = $store->orders->sum('total_price');

            $this->totalSelling += $store->orders->sum('total_price');
        }

        $this->chartData = [
            'series' => $series,
            'chart' => [
                'width' => 500,
                'type' => 'pie',
            ],
            'labels' => $labels,
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
}
