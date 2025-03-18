<?php

namespace App\Http\Livewire\Backend\Report\Performance\Form;

use App\Models\TargetSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Edit extends Component
{
    #[Reactive]
    public $targetId;

    public $target;

    public function render()
    {
        return view('livewire.backend.report.performance.form.edit');
    }

    #[Computed()]
    public function targetModel()
    {
        $targetModel = TargetSetting::with(['user' => function($query){
            $query->withTrashed();
        }])->find($this->targetId);
        return $targetModel;
    }

    public function submit()
    {
        $this->validate([
            'target' => ['required']
        ]);

        // remove comma
        $targetUncomma = str_replace(',', '', $this->target);

        $targetModel = TargetSetting::find($this->targetId);
        $targetModel->update([
            'target' => $targetUncomma
        ]);

        $this->dispatch('targetUpdated');
    }

}
