<?php


namespace App\Http\Livewire\Backend\Report\Trait;

use App\Models\OrderItem;
use App\Models\Store;
use Carbon\Carbon;
use Livewire\Attributes\Computed;

trait ReportData
{
    #[Computed]
    public function storeModel()
    {
        return Store::find($this->store);
    }

    #[Computed]
    public function month_string()
    {
        return Carbon::parse($this->month)->format('m');
    }

    #[Computed()]
    public function year_string()
    {
        return Carbon::parse($this->month)->format('Y');
    }

    /**
     * Get all semi custom orders
     * @param $store
     * @param $month
     * @param $year
     * @return mixed
     */
    protected function getSemicustom($store, $month, $year)
    {
        $semiCustom = OrderItem::with([
            'order',
            'product_sc',
            'order.payments',
            'order.customer',
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

        return $semiCustom;
    }

    /**
     * Get all ready to wear orders
     * @param $store
     * @param $month
     * @param $year
     * @return mixed
     */
    protected function getReadyToWear($store, $month, $year)
    {
        $readyToWear = OrderItem::with([
            'order',
            'product_rtw',
            'order.payments',
            'product_rtw.category',
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
            ->readyToWear()
            ->get();

        return $readyToWear;
    }

    /**
     * Range of days in a month
     */
    protected function rangeOfDays($month)
    {
        $dateRange = [];
        $date = Carbon::parse($month)->startOfMonth();
        $lastDateOfMonth = Carbon::parse($month)->endOfMonth()->format('Y-m-d');

        while ($date->lte($lastDateOfMonth)) {
            $dateRange[] = $date->format('d');
            $date->addDay();
        }
        return $dateRange;
    }

    protected function cacheKey($store, $month, $year)
    {
        return sprintf(
            'report-store_%s-%s-%s',
            $store,
            $month,
            $year
        );
    }

}
