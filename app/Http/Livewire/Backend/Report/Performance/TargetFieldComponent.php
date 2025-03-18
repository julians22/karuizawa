<?php

namespace App\Http\Livewire\Backend\Report\Performance;

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class TargetFieldComponent extends Component
{

    #[Reactive]
    public $crew;

    #[Reactive]
    public $month;

    #[Reactive]
    public $store;

    #[Computed()]
    public function crewTarget()
    {
        return \App\Models\TargetSetting::find($this->crew['target_id']);
    }

    public function render()
    {
        return view('livewire.backend.report.performance.target-field-component');
    }
}
