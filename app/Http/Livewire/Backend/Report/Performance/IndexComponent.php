<?php

namespace App\Http\Livewire\Backend\Report\Performance;

use App\Models\Order;
use App\Models\Store;
use Livewire\Attributes\On;
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

    public $categories = [];

    public $isEdit = false;
    public $isCreate = false;

    public $editTargetId = null;

    public function mount($categories = [])
    {
        $this->categories = $categories;

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
    }

    public function render()
    {
        return view('livewire.backend.report.performance.index-component');
    }

    public function createTarget()
    {
        $this->isCreate = true;
        $this->isEdit = false;
        $this->editTargetId = null;
    }

    #[On('editTarget')]
    public function editTarget($editTargetId)
    {
        $this->editTargetId = $editTargetId;
        $this->isEdit = true;
        $this->isCreate = false;
    }

    #[On('targetCreated')]
    public function targetCreated()
    {
        $this->isEdit = false;
        $this->isCreate = false;
        $this->editTargetId = null;
    }

    #[On('targetUpdated')]
    public function targetUpdated()
    {
        $this->isEdit = false;
        $this->isCreate = false;
        $this->editTargetId = null;
    }

    #[On('cancelCreate')]
    public function cancelCreate()
    {
        $this->isEdit = false;
        $this->isCreate = false;
        $this->editTargetId = null;
    }
}
