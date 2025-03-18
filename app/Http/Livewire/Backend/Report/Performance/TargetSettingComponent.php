<?php

namespace App\Http\Livewire\Backend\Report\Performance;

use App\Domains\Auth\Models\User;
use App\Models\OrderItem;
use App\Models\TargetSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class TargetSettingComponent extends Component
{
    public $month;
    public $store;
    public $category;

    public $isEdit = false;
    public $isCreate = false;

    public $editTargetId = null;

    public function mount($category, $month, $store)
    {
        $this->month = $month;
        $this->store = $store;
        $this->category = $category;
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
    }

    #[On('submitFilter')]
    public function submitFilter()
    {
        $this->reset('isEdit', 'isCreate', 'editTargetId');
    }

    public function editTarget($editTargetId)
    {
        $this->dispatch('editTarget', $editTargetId)->to(IndexComponent::class);
    }

    public function deleteTarget($targetId)
    {
        $target = TargetSetting::find($targetId);
        $target->delete();
    }

    public function render()
    {
        $crews = [];
        $targetsQuery = TargetSetting::where('month', $this->month)
            ->where('store_id', $this->store)
            ->where('category_id', $this->category->id)
            ->get();

        if (!$targetsQuery->isEmpty()) {
            $targetsQuery->each(function ($target) use (&$crews) {
                $user = User::withTrashed()->find($target->user_id);

                $actualSell = $this->getActualSell($user->id);

                $total = 0;

                if ($actualSell) {
                    $total = $actualSell->sum('total_price');
                }

                $indexPercent = ($total / $target->target) * 100;

                $crews[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'target' => $target->target,
                    'actual' => $total,
                    'percent' => number_format($indexPercent, 0),
                    'target_id' => $target->id
                ];
            });
        }

        return view('livewire.backend.report.performance.target-setting-component', [
            'crews' => $crews
        ]);
    }

    public function getActualSell($crewId)
    {

        $month = explode('-', $this->month)[1];
        $year = explode('-', $this->month)[0];
        $store = $this->store;

        $userId = $crewId;

        $readyToWear = OrderItem::with([
            'order',
            'product_rtw',
            'order.user' => function ($query) {
                $query->withTrashed();
            }
            ])
            ->whereHas('order', function ($query) use ($store, $month, $year, $userId) {
                return $query->whereMonth('order_date', $month)
                    ->whereYear('order_date', $year)
                    ->where('store_id', $store)
                    ->where('status', config('enums.order_status.completed'))
                    ->where('user_id', $userId);
            })
            ->whereHas('product_rtw', function ($query) {
                return $query->where('category_id', $this->category->id);
            })
            ->readyToWear()
            ->get();

        return $readyToWear ?? null;
    }
}
