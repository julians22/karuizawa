<?php

namespace App\Http\Livewire\Backend\Report\Performance\Result;

use App\Models\OrderItem;
use App\Models\TargetSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class IndexReport extends Component
{
    #[Reactive]
    public $month;

    #[Reactive]
    public $store;


    public $category;

    public function mount($category)
    {
        $this->category = $category;
    }

    protected function getTarget()
    {
        $targetMaster = TargetSetting::where('store_id', $this->store)
            ->where('month', $this->month)
            ->where('category_id', $this->category->id)
            ->with(['user' => function ($query) {
                $query->withTrashed();
            }]);

        return $targetMaster->get();
    }

    protected function getActualSell($crewId)
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

    protected function getAllActualSell()
    {
        $month = explode('-', $this->month)[1];
        $year = explode('-', $this->month)[0];
        $store = $this->store;

        $readyToWear = OrderItem::with([
            'order',
            'product_rtw',
            'order.user' => function ($query) {
                $query->withTrashed();
            }
            ])
            ->whereHas('order', function ($query) use ($store, $month, $year) {
                return $query->whereMonth('order_date', $month)
                    ->whereYear('order_date', $year)
                    ->where('store_id', $store)
                    ->where('status', config('enums.order_status.completed'));
            })
            ->whereHas('product_rtw', function ($query) {
                return $query->where('category_id', $this->category->id);
            })
            ->readyToWear()
            ->get();

        return $readyToWear ?? null;
    }

    public function render()
    {
        $targetMaster = $this->getTarget();
        $sumTarget = $targetMaster->sum('target');

        $actualSell = 0;

        foreach ($targetMaster as $target) {
            $actualSell += $this->getActualSell($target->user->id)->sum('total_price');
        }

        $percent = $sumTarget == 0 ? 0 : ($actualSell / $sumTarget) * 100;

        $allActualSell = $this->getAllActualSell();
        $totalActualSell = $allActualSell->sum('total_price');

        return view('livewire.backend.report.performance.result.index-report', [
            'sumTarget' => $sumTarget,
            'actualSell' => $actualSell,
            'allActualSell' => $totalActualSell,
            'percent' => number_format($percent, 0),
        ]);
    }
}
