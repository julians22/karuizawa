<?php

namespace App\Http\Livewire\Backend\Report;

use App\Http\Livewire\Backend\Report\Trait\ReportData;
use App\Models\Order;
use App\Models\Store;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class IndexComponent extends Component
{
    use ReportData;

    #[Url(keep: true)]
    public $month = '';

    #[Url(keep: true)]
    public $filtertype = 'monthly';

    public $date = '';

    public $categories;

    public $startMonth;
    public $endMonth;

    public $stores;

    public $reportData = [];

    public function mount($categories)
    {
        $this->stores = Store::all();

        // get first and last transaction date in database
        $firstTransaction = Order::orderBy('created_at', 'asc')->first();
        $lastTransaction = Order::orderBy('created_at', 'desc')->first();

        $this->startMonth = $firstTransaction->created_at->format('Y-m');
        $this->endMonth = $lastTransaction->created_at->format('Y-m');

        if ($this->month == '') {
            if ($this->filtertype == 'monthly') {
                $this->month = $lastTransaction->created_at->format('Y-m');
            }else{
                $this->date = $lastTransaction->created_at->format('Y-m-d');
            }

        }else{
            // check params is valid using regex
            if (preg_match('/^\d{4}-\d{2}$/', $this->month) == 0) {
                if ($this->filtertype == 'monthly') {
                    $this->month = $lastTransaction->created_at->format('Y-m');
                }else{
                    $this->date = $lastTransaction->created_at->format('Y-m-d');
                }
            }
        }

        $this->categories = $categories;

        $this->generateReportData();
    }

    #[Computed()]
    public function remaining_days()
    {
        $date = $this->date;
        $month = $this->month;

        if ($this->filtertype !== 'daily') {
            // check month is current month
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
            $date = $this->date;
        }

        $date = Carbon::parse($date);

        $daysInMonth = $date->daysInMonth;

        $remaining = $daysInMonth - $date->day;

        return $remaining;
    }

    #[Computed()]
    public function reportIsReady()
    {
        return $this->month != '' && $this->stores->count() > 0;
    }

    #[Computed()]
    public function isDaily()
    {
        return $this->filtertype == 'daily';
    }

    public function updatedDate()
    {
        if ($this->filtertype == 'daily') {
            $date = $this->date;
            // attach month from date
            $this->month = substr($date, 0, 7);
        }

        $this->generateReportData();
    }

    public function updatedFiltertype()
    {
        if ($this->filtertype == 'monthly') {
            $this->reset('date');
        }
    }

    public function updatedMonth()
    {
        $this->generateReportData();
    }

    public function generateReportData()
    {
        $this->reportData = [];
        $orderData = [];

        $daily = $this->isDaily() ? $this->date : null;

        $this->stores->each(function ($store) use (&$orderData) {
            $orderData[$store->id] = [];

            foreach ($this->categories as $category) {
                $orderData[$store->id][$category->name] = [
                    'value' => 0,
                    'qty' => 0
                ];
            }
        });

        $this->stores->each(function ($store) use (&$orderData) {
            $orderData[$store->id]['Semi Custom'] = [
                'value' => 0,
                'qty' => 0
            ];
        });

        $this->stores->each(function ($store) use (&$orderData, $daily) {
            $readyToWear = $this->getReadyToWear($store->id, $this->month_string, $this->year_string, $daily);
            $readyToWear->each(function ($item) use (&$orderData, $store) {
                $orderData[$store->id][$item->product->category->name]['value'] += $item->total_price;
                $orderData[$store->id][$item->product->category->name]['qty'] += $item->quantity;
            });

        });

        $this->stores->each(function ($store) use (&$orderData, $daily) {
            $semiCustom = $this->getSemicustom($store->id, $this->month_string, $this->year_string, $daily);
            $semiCustom->each(function ($item) use (&$orderData, $store) {
                $orderData[$store->id]['Semi Custom']['value'] += $item->price;
                $orderData[$store->id]['Semi Custom']['qty'] += $item->quantity;
            });
        });

        $this->reportData = $orderData;
    }

    public function render()
    {
        return view('livewire.backend.report.index-component');
    }
}
