<?php

namespace App\Http\Livewire\Backend\Report\Performance;

use App\Models\Order;
use App\Models\Store;
use Livewire\Attributes\Url;
use Livewire\Component;

class IndexComponent extends Component
{
    #[Url(keep: true)]
    public $month = '';

    public $startMonth;
    public $endMonth;

    public $stores;

    #[Url(keep: true)]
    public $store = '';

    public $crewGroups = [];

    public function mount()
    {
        $this->stores = Store::all();

        // get first and last transaction date in database
        $firstTransaction = Order::orderBy('created_at', 'asc')->first();
        $lastTransaction = Order::orderBy('created_at', 'desc')->first();

        $this->startMonth = $firstTransaction->created_at->format('Y-m');
        $this->endMonth = $lastTransaction->created_at->format('Y-m');

        if ($this->month == '') {
            $this->month = $lastTransaction->created_at->format('Y-m');
        }else{
            // check params is valid using regex
            if (preg_match('/^\d{4}-\d{2}$/', $this->month) == 0) {
                $this->month = $lastTransaction->created_at->format('Y-m');
            }
        }
    }

    public function submitFilter()
    {
        $this->dispatch('submitFilter');

        $this->validate([
            'month' => ['required', 'date_format:Y-m'],
            'store' => ['required', 'exists:stores,id']
        ]);

        $months = explode('-', $this->month);
        $month = $months[1];
        $year = $months[0];

        $transctionGroupByUser = Order::where('store_id', $this->store)
            ->where('status', config('enums.order_status.completed'))
            ->whereMonth('order_date', $month)
            ->whereYear('order_date', $year)
            ->get();

        $crewIds = [];

        $transctionGroupByUser->groupBy('user_id')->each(function ($item, $key) use (&$crewIds) {
            $crewIds[] = $key;
        });

        $this->crewGroups = $crewIds;
    }

    public function render()
    {
        return view('livewire.backend.report.performance.index-component');
    }
}
