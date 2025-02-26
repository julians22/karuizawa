<?php

namespace App\Http\Livewire\Backend\Report;

use App\Models\Order;
use App\Models\Store;
use Livewire\Attributes\Url;
use Livewire\Component;

class IndexComponent extends Component
{

    #[Url()]
    public $month = '';

    #[Url()]
    public $store = '';

    public $startMonth;
    public $endMonth;

    public $reportIsReady = false;

    public function mount()
    {
        // get first and last transaction date in database
        $firstTransaction = Order::orderBy('created_at', 'asc')->first();
        $lastTransaction = Order::orderBy('created_at', 'desc')->first();

        $this->startMonth = $firstTransaction->created_at->format('Y-m');
        $this->endMonth = $lastTransaction->created_at->format('Y-m');
    }

    public function render()
    {
        $storeData = Store::all();
        return view('livewire.backend.report.index-component', compact('storeData'));
    }

    public function applyFilter()
    {
        $this->validate([
            'month' => 'required',
            'store' => 'required',
        ]);

        $this->reportIsReady = true;
    }
}
