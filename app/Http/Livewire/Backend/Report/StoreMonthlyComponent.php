<?php

namespace App\Http\Livewire\Backend\Report;

use App\Http\Livewire\Backend\Report\Trait\ReportData;
use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Component;

class StoreMonthlyComponent extends Component
{

    use ReportData;

    public $month;
    public $store;

    public function mount($month, $store)
    {
        $this->month = $month;
        $this->store = $store;
    }

    public function render()
    {

        $data = [];


        foreach ($this->categories as $category) {
            $data[$category->name] = [
                'value' => 0,
                'qty' => 0
            ];
        }

        $data['Semi Custom'] = [
            'value' => 0,
            'qty' => 0
        ];

        $readyToWear = $this->getReadyToWear($this->store->id, $this->month_string, $this->year_string);

        $semiCustom = $this->getSemicustom($this->store->id, $this->month_string, $this->year_string);

        $readyToWear->each(function ($item) use (&$data) {
            $data[$item->product->category->name]['value'] += $item->total_price;
            $data[$item->product->category->name]['qty'] += $item->quantity;
        });

        $semiCustom->each(function ($item) use (&$data) {
            $data['Semi Custom']['value'] += $item->price;
            $data['Semi Custom']['qty'] += $item->quantity;
        });


        return view('livewire.backend.report.store-monthly-component', compact('data'));
    }

    #[Computed()]
    public function categories()
    {
        return Category::all();
    }
}
