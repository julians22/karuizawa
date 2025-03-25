<?php

namespace App\Http\Livewire\Backend\Report\Performance;

use App\Domains\Auth\Models\User;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\TargetSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class TargetSettingComponent extends Component
{
    public $month;
    public $store;
    public $selectedCrew;

    public $categories;

    public function mount($month, $store, $selectedCrew, $categories)
    {
        $this->month = $month;
        $this->store = $store;
        $this->selectedCrew = $selectedCrew;

        $this->categories = $categories;
    }

    public function render()
    {
        $transactions = $this->getCrewTransaction();
        $actualSellingQty = [];
        $actualSellingVal = [];

        foreach ($this->categories as $value) {
            $actualSellingQty[$value->name] = 0;
            $actualSellingVal[$value->name] = 0;
        }

        $actualSellingQty['Semi Custom'] = 0;
        $actualSellingVal['Semi Custom'] = 0;

        collect($transactions['readyToWear'])->each(function ($item) use (&$actualSellingQty, &$actualSellingVal) {
            $actualSellingQty[$item->product_rtw->category->name] += $item->quantity;
            $actualSellingVal[$item->product_rtw->category->name] += $item->total_price;
        });

        collect($transactions['semiCustom'])->each(function ($item) use (&$actualSellingQty, &$actualSellingVal) {
            $actualSellingQty['Semi Custom'] += $item->quantity;
            $actualSellingVal['Semi Custom'] += $item->price;
        });

        $totalSellingQty = collect($actualSellingQty)->sum();
        $totalSellingVal = collect($actualSellingVal)->sum();

        return view('livewire.backend.report.performance.target-setting-component', [
            'actualSellingVal' => $actualSellingVal,
            'actualSellingQty' => $actualSellingQty,
            'totalSellingQty' => $totalSellingQty,
            'totalSellingVal' => $totalSellingVal,
        ]);
    }

    #[Computed()]
    public function crew()
    {
        return User::withTrashed()->find($this->selectedCrew);
    }

    #[Computed()]
    public function crew_target() {
        $targetSetting = $this->crew->targetSettings->where('month', $this->month)->where('store_id', $this->store);

        return $targetSetting->load('category');
    }

    #[On('targetUpdated')]
    public function refreshTarget()
    {
        $this->crew_target();
    }

    public function generateTargetSettings()
    {
        // delete all target settings
        TargetSetting::where('month', $this->month)
            ->where('store_id', $this->store)
            ->where('user_id', $this->selectedCrew)
            ->delete();

        $categories = Category::all();

        $categories->each(function ($category) {
            $target = new TargetSetting();
            $target->month = $this->month;
            $target->store_id = $this->store;
            $target->category_id = $category->id;
            $target->user_id = $this->selectedCrew;
            $target->target = 0;
            $target->save();
        });

        $target = new TargetSetting();

        $target->month = $this->month;
        $target->store_id = $this->store;
        $target->is_semicustom = 1;
        $target->category_id = null;
        $target->user_id = $this->selectedCrew;
        $target->target = 0;

        $target->save();
    }

    public function getCrewTransaction() : array
    {
        $data = [];

        $crewId = $this->selectedCrew;

        $monthSplit = explode('-', $this->month);

        $data['readyToWear'] = $this->getReadtoWear($crewId, $this->store, $monthSplit[1], $monthSplit[0]);
        $data['semiCustom'] = $this->getSemiCustom($crewId, $this->store, $monthSplit[1], $monthSplit[0]);

        return $data;
    }

    protected function getReadtoWear($crewId, $store, $month, $year)
    {
        $trans = OrderItem::with([
            'order',
            'product_rtw',
            'product_rtw.category',
            'order.user' => function ($query) {
                $query->withTrashed();
            }
            ])
            ->whereHas('order', function ($query) use ($store, $month, $year, $crewId) {
                return $query->whereMonth('order_date', $month)
                    ->whereYear('order_date', $year)
                    ->where('store_id', $store)
                    ->where('status', config('enums.order_status.completed'))
                    ->where('user_id', $crewId);
            })
            ->readyToWear()
            ->get();


        return $trans;
    }

    protected function getSemiCustom($crewId, $store, $month, $year)
    {
        $trans = OrderItem::with([
            'order',
            'product_sc',
            'order.user' => function ($query) {
                $query->withTrashed();
            }
            ])
            ->whereHas('order', function ($query) use ($store, $month, $year, $crewId) {
                return $query->whereMonth('order_date', $month)
                    ->whereYear('order_date', $year)
                    ->where('store_id', $store)
                    ->where('status', config('enums.order_status.completed'))
                    ->where('user_id', $crewId);
            })
            ->semiCustom()
            ->get();

        return $trans;
    }



}
