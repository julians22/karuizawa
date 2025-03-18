<?php

namespace App\Http\Livewire\Backend\Report\Performance\Result;

use App\Models\OrderItem;
use App\Models\TargetSetting;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class SemiCustom extends Component
{
    #[Reactive]
    public $month;

    #[Reactive]
    public $store;

    protected function getTarget()
    {
        $targetMaster = TargetSetting::where('store_id', $this->store)
            ->where('month', $this->month)
            ->with(['user' => function ($query) {
                $query->withTrashed();
            }])
            ->semiCustom();

        return $targetMaster->get();
    }

    protected function getActualSell($crewId)
    {
        $month = explode('-', $this->month)[1];
        $year = explode('-', $this->month)[0];
        $store = $this->store;

        $userId = $crewId;

        $semiCustom = OrderItem::with([
            'order',
            'product_sc',
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
        ->semiCustom()
        ->get();

        return $semiCustom ?? null;
    }

    protected function getAllActualSell()
    {
        $month = explode('-', $this->month)[1];
        $year = explode('-', $this->month)[0];
        $store = $this->store;

        $semiCustom = OrderItem::with([
            'order',
            'product_sc',
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
        ->semiCustom()
        ->get();

        return $semiCustom ?? null;
    }

    public function render()
    {

        $targetMaster = $this->getTarget();
        $sumTarget = $targetMaster->sum('target');

        $actualSell = 0;

        foreach ($targetMaster as $target) {
            $actualSell += $this->getActualSell($target->user->id)->sum('price');
        }

        $percent = $sumTarget == 0 ? 0 : ($actualSell / $sumTarget) * 100;

        $allActualSell = $this->getAllActualSell();
        $totalActualSell = $allActualSell->sum('price');


        return view('livewire.backend.report.performance.result.semi-custom', [
            'sumTarget' => $sumTarget,
            'actualSell' => $actualSell,
            'allActualSell' => $totalActualSell,
            'percent' => number_format($percent, 0),
        ]);
    }
}
