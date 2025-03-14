<?php

namespace App\Http\Livewire\Backend\Report\Performance;

use App\Domains\Auth\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class TargetSettingComponent extends Component
{
    #[Reactive]
    public $targetCrews = [];

    #[Reactive]
    public $month;

    #[Reactive]
    public $store;

    #[Computed()]
    public function requrementIsMet()
    {
        return $this->month != '' && $this->store != '';
    }

    #[Computed()]
    public function storeName()
    {
        if ($this->requrementIsMet) {
            $store = \App\Models\Store::find($this->store);

            return $store->name;
        }
        return '';
    }

    public function autoGenerateTarget()
    {
        if ($this->requrementIsMet) {

            foreach ($this->targetCrews as $crew) {
                $target = \App\Models\TargetSetting::firstOrCreate([
                    'user_id' => $crew->id,
                    'store_id' => $this->store,
                    'month' => $this->month
                ], [
                    'target' => 0
                ]);
            }
        }

        $this->pull('targetCrews');
        $this->pull('month');
        $this->pull('store');
    }

    public function render()
    {
        return view('livewire.backend.report.performance.target-setting-component');
    }
}
