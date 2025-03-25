<?php

namespace App\Http\Livewire\Backend\Report;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Store;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TargetCalculationComponent extends Component
{
    public $store;
    public $reportData;
    public $month;
    public $remainingDays;
    public $date;

    public $isDaily = false;


    public function mount($store, $reportData, $month, $remainingDays, $date = null)
    {
        $this->store = $store;
        $this->reportData = $reportData;
        $this->month = $month;
        $this->remainingDays = $remainingDays;

        if ($date === null) {

            $this->isDaily = false;

            if ($month == now()->format('Y-m')) {
                // set date to last transaction date
                $lastTransaction = Order::whereMonth('created_at', Carbon::parse($month)->month)
                    ->whereYear('created_at', Carbon::parse($month)->year)
                    ->orderBy('created_at', 'desc')
                    ->first();
                $date = $lastTransaction->created_at->format('Y-m-d');
            }else{
                $date = Carbon::parse($month)->endOfMonth()->format('Y-m-d');
            }
        }else{
            $date = Carbon::parse($date)->format('Y-m-d');

            $this->isDaily = true;
        }

        $this->date = $date;
    }

    public function render()
    {
        return view('livewire.backend.report.target-calculation-component');
    }


    #[Computed()]
    public function storeModel()
    {
        return Store::find($this->store);
    }


    #[Computed()]
    public function actualSelling()
    {
        $totalSelling = 0;
        if (!$this->isDaily) {

            foreach ($this->reportData as $data) {
                $totalSelling += $data['value'];
            }

            return $totalSelling;
        }

        $totalSelling = $this->supplyTotal();

        return $totalSelling;
    }

    // Get Report data till date for the month
    public function supplyTotal()
    {
        $readyToWear = OrderItem::with([
            'order',
        ])
        ->whereHas('order', function ($query) {
            return $query->whereMonth('order_date', Carbon::parse($this->month)->month)
                ->whereYear('order_date', Carbon::parse($this->month)->year)
                ->whereDate('order_date', '<=', $this->date)
                ->where('store_id', $this->store)
                ->where('status', config('enums.order_status.completed'));
        })
        ->readyToWear()
        ->get();

        $semiCustom = OrderItem::with([
            'order',
        ])
        ->whereHas('order', function ($query) {
            return $query->whereMonth('order_date', Carbon::parse($this->month)->month)
                ->whereYear('order_date', Carbon::parse($this->month)->year)
                ->whereDate('order_date', '<=', $this->date)
                ->where('store_id', $this->store)
                ->where('status', config('enums.order_status.completed'));
        })
        ->semiCustom()
        ->get();

        $totalSelling = 0;

        foreach ($readyToWear as $item) {
            $totalSelling += $item->total_price;
        }

        foreach ($semiCustom as $item) {
            $totalSelling += $item->price;
        }

        return $totalSelling;
    }

    #[Computed()]
    public function target()
    {
        $targets = $this->storeModel->targets()->where('month', $this->month)->get();
        return $targets->sum('target');
    }

    #[Computed()]
    public function remainingTarget()
    {
        return $this->target - $this->actualSelling;
    }


}
