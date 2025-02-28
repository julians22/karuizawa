<?php

namespace App\Http\Livewire\Backend\Report;

use App\Exports\GroupCategoryExport;
use App\Http\Livewire\Backend\Report\Trait\ReportData;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CategoryValueReportComponent extends Component
{
    use ReportData;

    #[Reactive]
    public $month = "";

    public $stores;

    public function mount($month, $stores)
    {
        $this->month = $month;
        $this->stores = $stores;
    }

    public function render()
    {
        return view('livewire.backend.report.category-value-report-component');
    }

    public function generateReportData($store)
    {
        $dateRange = $this->rangeOfDays($this->month);


        if($store && $this->month) {

            $templateColumns = array_merge(['Crew Name'], $dateRange);

            $data = [];

            $readyToWear = $this->getReadyToWear($store, $this->month_string, $this->year_string);

            $productCategoies = $readyToWear->map(function ($item) {
                return $item->product->category->name;
            })->unique()->toArray();

            $crews = $readyToWear->map(function ($item) {
                return $item->order->user->name;
            })->unique()->toArray();

            foreach ($productCategoies as $category) {
                $data[$category] = [];

                foreach ($crews as $crew) {
                    $data[$category][$crew] = [];

                    foreach ($this->rangeOfDays($this->month) as $key => $value) {
                        $data[$category][$crew][$value] = 0;
                    }
                }
            }

            $readyToWear->each(function ($item) use (&$data) {

                $date = $item->order->created_at->format('d');
                $category = $item->product->category->name;
                $crew = $item->order->user->name;

                $data[$category][$crew][$date] += $item->total_price;
            });

            $semiCustom = $this->getSemiCustom($store, $this->month_string, $this->year_string);

            $rows = [];

            $configures = [
                'headers' => []
            ];

            foreach ($data as $category => $crews) {

                $rows[] = [$category];

                $configures['headers'][count($rows)] =  [
                    'font' => [
                        'fontSize' => 16,
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4BACC6']]
                ];

                $rows[] = $templateColumns;

                $configures['headers'][count($rows)] =  [
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFFF00']]
                ];

                foreach ($crews as $crew => $values) {
                    $row = [$crew];

                    foreach ($values as $value) {
                        $row[] = $value;
                    }

                    $rows[] = $row;
                }

                // add 2 empty rows
                $rows[] = [' '];
                $rows[] = [' '];
            }

            // export to excel
            return (new GroupCategoryExport(collect($rows)->values(), $configures))->download('group_category.xlsx');
        }
    }
}
