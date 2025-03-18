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
    public $category;

    public $categories;

    public function mount($categories, $crewData = [])
    {
        $this->categories = $categories;
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
                ->withTrashed()
                ->get();

            return $users;
        }
        return [];
    }

    public function addTarget()
    {
        $this->validate([
            'crew' => ['required'],
            'target' => ['required']
        ]);

        $target = str_replace(',', '', $this->target);


        TargetSetting::create([
            'user_id' => $this->crew,
            'store_id' => $this->store,
            'month' => $this->month,
            'target' => $target,
            'category_id' => $this->category !== 'semicustom' ? $this->category : null,
            'is_semicustom' => $this->category === 'semicustom' ? true : false
        ]);

        $user = User::withTrashed()->find($this->crew);

        $this->crewData[] = [
            'id' => $this->crew,
            'name' => $user->name,
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
