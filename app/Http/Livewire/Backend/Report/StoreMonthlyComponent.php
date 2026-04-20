<?php

namespace App\Http\Livewire\Backend\Report;

use App\Http\Livewire\Backend\Report\Trait\ReportData;
use App\Models\Category;
use App\Models\Order;
use App\Models\Store;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class StoreMonthlyComponent extends Component
{
    use ReportData;

    public $brand;
    public Store $store;
    public $month;

    public $date;
    public $isDaily = false;

    public $reportData = [];

    public function mount(
        $brand,
        $store,
        $month,
        $date = null
    )
    {
        $this->brand = $brand;
        $this->store = $store;
        $this->month = $month;

        if ($date === null) {

            $this->isDaily = false;
        }else{
            $this->isDaily = true;
        }

        $this->date = $date;

        $this->getReportData();
    }

    public function render()
    {
        return view('livewire.backend.report.store-monthly-component');
    }


    protected function getReportData()
    {
        $data = [];
        $getTransactions = $this->getReadyToWear($this->store->id, $this->month_string, $this->year_string, $this->brand, $this->date);

        // generate array
        $categories = Category::whereHas('products', function ($query) {
            $query->where('brand_id', $this->brand);
        })->get();

        foreach ($categories as $category) {
            $data[$category->name] = [
                'value' => 0,
                'qty' => 0
            ];
        }

        if ($this->brand == 2) {
            $data['Semi Custom'] = [
                'value' => 0,
                'qty' => 0
            ];

            $semiCustom = $this->getSemicustom($this->store->id, $this->month_string, $this->year_string,$this->date ?? null);
            $semiCustom->each(function ($item) use (&$data) {
                $data['Semi Custom']['value'] += $item->price;
                $data['Semi Custom']['qty'] += $item->quantity;
            });
        }


        $this->reportData = $getTransactions->each(function ($item) use (&$data) {
            $data[$item->product->category->name]['value'] += $item->total_price;
            $data[$item->product->category->name]['qty'] += $item->quantity;
        });

        $this->reportData = $data;
    }


}
