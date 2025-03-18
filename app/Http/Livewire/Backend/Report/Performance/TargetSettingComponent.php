<?php

namespace App\Http\Livewire\Backend\Report\Performance;

use App\Domains\Auth\Models\User;
use App\Models\TargetSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
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

    public $isEdit = false;
    public $isCreate = false;

    public $editTargetId = null;

    public $crewData = [];

    public function mount()
    {
        $this->getCrewData();
    }

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

            return $store->name ?? '';
        }
        return '';
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
        $this->isCreate = false;
    }

    #[On('targetCreated')]
    public function targetCreated()
    {
        $this->isEdit = false;
        $this->isCreate = false;
        $this->editTargetId = null;

        $this->getCrewData();
    }

    public function getTargetFromTransactions()
    {
        if ($this->requrementIsMet) {
            foreach ($this->targetCrews as $crew) {
                $target = \App\Models\TargetSetting::firstOrCreate([
                    'user_id' => $crew,
                    'store_id' => $this->store,
                    'month' => $this->month
                ], [
                    'target' => 0
                ]);
            }
        }
        $this->getCrewData();

        $this->reset('isEdit', 'isCreate', 'editTargetId');
    }

    #[On('submitFilter')]
    public function submitFilter()
    {
        $this->getCrewData();

        $this->reset('isEdit', 'isCreate', 'editTargetId');
    }

    public function getCrewData()
    {
        $this->reset('crewData');
        $crews = [];
        $targetsQuery = TargetSetting::where('month', $this->month)
            ->where('store_id', $this->store)
            ->get();

        if (!$targetsQuery->isEmpty()) {
            $targetsQuery->each(function ($target) use (&$crews) {
                $user = User::withTrashed()->find($target->user_id);

                $crews[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'target' => $target->target,
                    'target_id' => $target->id
                ];
            });
        }

        $this->crewData = $crews;
    }

    public function editTarget($editTargetId)
    {
        $this->editTargetId = $editTargetId;
        $this->isEdit = true;
        $this->isCreate = false;
    }

    public function deleteTarget($targetId)
    {
        $target = TargetSetting::find($targetId);
        $target->delete();

        $this->getCrewData();
    }

    public function createTarget()
    {
        $this->isCreate = true;
        $this->isEdit = false;
        $this->editTargetId = null;
    }

    public function render()
    {
        return view('livewire.backend.report.performance.target-setting-component');
    }
}
