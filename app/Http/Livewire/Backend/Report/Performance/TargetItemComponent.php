<?php

namespace App\Http\Livewire\Backend\Report\Performance;

use App\Models\TargetSetting;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TargetItemComponent extends Component
{
    public $target;
    public $actualvalue;
    public $actualqty;

    public $targetNew = 0;

    public $isEdit = false;

    public function mount(TargetSetting $target, $actualvalue = 0, $actualqty = 0)
    {
        $target->load('category');
        $this->target = $target;
        $this->actualvalue = $actualvalue;
        $this->actualqty = $actualqty;
    }

    public function render()
    {
        return view('livewire.backend.report.performance.target-item-component');
    }

    #[Computed()]
    public function indexPercent()
    {
        if ($this->target->target == 0) {
            return 0;
        }

        return round(($this->actualvalue / $this->target->target) * 100, 2);
    }

    public function updateTarget()
    {
        $this->validate([
            'targetNew' => 'required|min:0'
        ]);

        // remove comma, dot, and space
        $targetNew = preg_replace('/[^0-9]/', '', $this->targetNew);

        $this->target->update([
            'target' => $targetNew
        ]);

        $this->isEdit = false;

        $this->dispatch('targetUpdated');
    }


    public function cancelEdit()
    {
        $this->reset('targetNew', 'isEdit');
    }

    public function editTarget()
    {
        $this->isEdit = true;
        $this->targetNew = number_format($this->target->target, 0, ',', '.');
    }


}
