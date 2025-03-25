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

    #[Reactive]
    public $daily = null;

    public $chartData = [];
    public $totalSelling = 0;

    public $reportData;
    public $stores;

    public function render()
    {
        return view('livewire.backend.chart.store-transaction-chart-component');
    }

    public function mount($reportData, $stores)
    {
        $this->reportData = $reportData;
        $this->stores = $stores;

        $this->generateReportData();

        $this->dispatch('chartDataFilled');
    }

    public function generateReportData()
    {
        $this->chartData = [];
        $this->totalSelling = 0;

        $series = [];
        $labels = [];

        $this->stores->each(function ($store, $key) use (&$series, &$labels) {

            $labels[$store->id] = $store->name;
            $series[$store->id] = 0;
        });

        collect($this->reportData)->each(function ($data, $key) use (&$series) {
            collect($data)->each(function ($item) use (&$series, $key) {
                $series[$key] += $item['value'];
            });
        });

        // simplify the key of series and labels
        $series = array_values($series);
        $labels = array_values($labels);

        foreach ($series as $key => $value) {
            $this->totalSelling += $value;
        }

        $this->chartData = [
            'series' => $series,
            'chart' => [
                'width' => 350,
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
