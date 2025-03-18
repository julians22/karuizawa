<?php

namespace App\Http\Livewire\Backend\Report\Performance\Form;

use App\Domains\Auth\Models\User;
use App\Models\TargetSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Create extends Component
{

    #[Reactive]
    public $month;

    #[Reactive]
    public $store;

    public $crewData;

    public $crew;
    public $target;

    public function mount($crewData)
    {
        $this->crewData = $crewData;
    }

    #[Computed()]
    public function requrementIsMet()
    {
        return $this->month != '' && $this->store != '';
    }

    #[Computed()]
    public function availableCrews()
    {
        if ($this->requrementIsMet) {

            $usersId = collect($this->crewData)->pluck('id')->toArray();

            $users = User::whereNotIn('id', $usersId)
                ->where('type', User::TYPE_CREW)
                ->get();

            return $users;
        }
        return [];
    }

    public function addTarget()
    {
        $this->validate([
            'crew' => ['required'],
            'target' => ['required', 'numeric']
        ]);


        TargetSetting::create([
            'user_id' => $this->crew,
            'store_id' => $this->store,
            'month' => $this->month,
            'target' => $this->target
        ]);

        $this->crewData[] = [
            'id' => $this->crew,
            'name' => User::find($this->crew)->name,
            'target' => $this->target
        ];

        $this->reset('crew', 'target');

        $this->dispatch('targetCreated');
    }

    public function cancelCreate()
    {
        $this->reset('crew', 'target');

        $this->dispatch('cancelCreate');
    }

    public function render()
    {
        return view('livewire.backend.report.performance.form.create');
    }
}
