<?php

namespace App\Http\Livewire\Backend\Report;

use App\Http\Livewire\Backend\Report\Trait\ReportData;
use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class StoreMonthlyComponent extends Component
{
    public $reportData;

    public function mount($reportData)
    {
        $this->reportData = $reportData;
    }

    public function render()
    {
        return view('livewire.backend.report.store-monthly-component');
    }
}
