<?php

namespace App\Http\Livewire\Backend\Report\Performance;

use Livewire\Attributes\Computed;
use Livewire\Component;

class TargetFieldComponent extends Component
{
    public $crew;
    public $month;
    public $store;

    public function mount($crew, $month, $store)
    {
        $this->crew = $crew;
        $this->month = $month;
        $this->store = $store;
    }

    #[Computed()]
    public function crewTarget()
    {
        $target = \App\Models\TargetSetting::firstOrCreate([
            'user_id' => $this->crew->id,
            'store_id' => $this->store,
            'month' => $this->month
        ], [
            'target' => 0
        ]);

        return $target;
    }

    public function render()
    {
        return view('livewire.backend.report.performance.target-field-component');
    }
}
