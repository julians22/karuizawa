<?php

namespace App\Http\Livewire\Backend\Report;

use App\Models\Order;
use App\Models\Store;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class IndexComponent extends Component
{

    #[Url(keep: true)]
    public $month = '';

    public $startMonth;
    public $endMonth;

    public $stores;

    public function mount()
    {
        $this->stores = Store::all();

        // get first and last transaction date in database
        $firstTransaction = Order::orderBy('created_at', 'asc')->first();
        $lastTransaction = Order::orderBy('created_at', 'desc')->first();

        $this->startMonth = $firstTransaction->created_at->format('Y-m');
        $this->endMonth = $lastTransaction->created_at->format('Y-m');

        $this->month = $lastTransaction->created_at->format('Y-m');

    }

    #[Computed()]
    public function reportIsReady()
    {
        return $this->month != '' && $this->stores->count() > 0;
    }

    public function render()
    {
        return view('livewire.backend.report.index-component');
    }
}
