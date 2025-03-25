<?php

namespace App\Http\Livewire\Backend\Report\Performance;

use App\Models\Order;
use App\Models\Store;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class IndexComponent extends Component
{
    #[Url(keep: true)]
    public $month = '';

    #[Url(keep: true)]
    public $store = '';

    #[Url(keep: true)]
    public $selectedCrew = null;


    public $startMonth;
    public $endMonth;

    public $stores;


    #[Validate('required|string|min:3')]
    public $crewName = null;

    public $crewList;

    public $categories;
    public $crews;

    public function mount($categories = [], $crews)
    {
        $this->categories = $categories;
        $this->crews = $crews;

        $this->crewList = $this->crews->pluck('name', 'id');

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

    #[Computed()]
    public function isReady()
    {
        return $this->month != '' && $this->store != '';
    }

    public function submitFilter()
    {
        $this->dispatch('filterSubmit');
    }

    public function render()
    {
        return view('livewire.backend.report.performance.index-component');
    }
}
