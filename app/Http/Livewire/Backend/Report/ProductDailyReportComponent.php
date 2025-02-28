<?php

namespace App\Http\Livewire\Backend\Report;

use App\Exports\DailyReport;
use App\Http\Livewire\Backend\Report\Trait\ReportData;
use App\Models\OrderItem;
use App\Models\Store;
use Cache;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;


class ProductDailyReportComponent extends Component
{
    use ReportData;

    #[Reactive]
    public $month = "";

    public $stores;

    public function mount($month, $stores)
    {
        $this->month = $month;
        $this->stores = $stores;
    }

    public function render()
    {
        return view('livewire.backend.report.product-daily-report-component');
    }

    public function generateReportData($store){

        // create date range
        $dateRange = $this->rangeOfDays($this->month);

        if ($store && $this->month) {

            $data = [];

            foreach ($dateRange as $key => $value) {
                $data[$value] = [];
            }

            // prepare data with caching
            // if the month is not older than the current month, don't cache the result
            if(!is_older_month_year($this->month_string, $this->year_string)){
                $semiCustom = $this->getSemicustom($store, $this->month_string, $this->year_string);
            }else{
                // cache the result for 1 day
                $semiCustom = Cache::remember($this->cacheKey($store, $this->month_string, $this->year_string) . '-semi_custom', 60 * 24, function () use ($store) {
                    return $this->getSemicustom($store, $this->month_string, $this->year_string);
                });
            }

            $semiCustom->each(function ($item) use (&$data) {
                $date = Carbon::parse($item->order->order_date)->format('d');

                $data[$date]['sc'][] = [
                    'order_no_sc' => $item->order->order_number,
                    'customer_name_sc' => $item->order->customer->full_name,
                    'customer_phone_sc' => $item->order->customer->phone,
                    'handling_date_sc' => $item->product_sc->handling_date,
                    'fabric_code_sc' => $item->product_sc->fabric_code,
                    'fabric_name_sc' => $item->product_sc->fabric_name,
                    'collar_sc' => $item->product_sc->collar,
                    'cuff_sc' => $item->product_sc->cuff,
                    'front_body_sc' => $item->product_sc->front_body,
                    'pocket_sc' => $item->product_sc->pocket,
                    'back_body_sc' => $item->product_sc->back_body,
                    'button_sc' => $item->product_sc->button,
                    'customer_type_sc' => $item->product_sc->order_type_customer,
                    'body_type_sc' => $item->product_sc->body_type,
                    'sleeve_sc' => $item->product_sc->sleeve,
                    'quantity_sc' => $item->quantity,
                    'price_sc' => $item->product_sc->base_price,
                    'discount_sc' => $item->product_sc->base_discount . '%',
                    'option_sc' => $item->product_sc->option_total,
                    'option_plus_sc' => $item->product_sc->option_additional_price,
                    'gift_card_sc' => $item->product_sc->option_discount,
                    'total_sc' => $item->price,
                    'payment_method_sc' => $item->order->payments->first()->payment
                ];
            });

            // prepare data with caching
            // if the month is not older than the current month, don't cache the result
            if(!is_older_month_year($this->month_string, $this->year_string)){
                $readyToWear = $this->getReadyToWear($store, $this->month_string, $this->year_string);
            }else{
                // cache the result for 1 day
                $readyToWear = Cache::remember($this->cacheKey($store, $this->month_string, $this->year_string) . '-ready_to_wear', 60 * 24, function () use ($store) {
                    return $this->getReadyToWear($store, $this->month_string, $this->year_string);
                });
            }

            $readyToWear->each(function ($item) use (&$data) {
                $date = Carbon::parse($item->order->order_date)->format('d');
                $data[$date]['rtw'][] = [
                    "sku" => $item->product_rtw->sku,
                    "category" => $item->product_rtw->category->name,
                    "quantity" => $item->quantity,
                    "price" => $item->price,
                    "discount" => $item->discount_percentage . '%',
                    "total" => $item->total_price,
                    "crew" => $item->order->user->name,
                    "payment_method" => $item->order->payments->first()->payment
                ];
            });

            // Generate array key for excel
            $keyBody = [
                'no_rtw',
                'sku',
                'category',
                'quantity',
                'price',
                'discount',
                'total',
                'crew',
                'payment_method',
                'SPACER1',
                'SPACER2',
                'no_sc',
                'order_no_sc',
                'customer_name_sc',
                'customer_phone_sc',
                'handling_date_sc',
                'fabric_code_sc',
                'fabric_name_sc',
                'collar_sc',
                'cuff_sc',
                'front_body_sc',
                'pocket_sc',
                'back_body_sc',
                'button_sc',
                'customer_type_sc',
                'body_type_sc',
                'sleeve_sc',
                'quantity_sc',
                'price_sc',
                'discount_sc',
                'option_sc',
                'option_plus_sc',
                'gift_card_sc',
                'total_sc',
                'payment_method_sc'
            ];

            $exportedData = [];

            // prepare data for excel from $data
            foreach ($data as $key => $value) {
                $exportedData[$key] = [];
                $exportedData[$key]['body'] = [];
            }

            foreach ($data as $key => $value) {

                // compare length sc and rtw, and take bigger length
                $sc = isset($data[$key]['sc']) ? count($data[$key]['sc']) : 0;
                $rtw = isset($data[$key]['rtw']) ? count($data[$key]['rtw']) : 0;

                $length = $sc > $rtw ? $sc : $rtw;

                for ($i = 0; $i < $length; $i++) {
                    $exportedData[$key]['body'][$i] = [];
                }
            }

            foreach ($exportedData as $key => $value) {

                for ($i = 0; $i < count($value['body']); $i++) {

                    // fill keys
                    $exportedData[$key]['body'][$i] = array_fill_keys($keyBody, '');


                    if (isset($data[$key]['rtw'][$i])) {
                        $exportedData[$key]['body'][$i] = array_merge($exportedData[$key]['body'][$i], $data[$key]['rtw'][$i]);
                        $exportedData[$key]['body'][$i]['no_rtw'] = $i + 1;
                    }

                    if (isset($data[$key]['sc'][$i])) {
                        $exportedData[$key]['body'][$i] = array_merge($exportedData[$key]['body'][$i], $data[$key]['sc'][$i]);
                        $exportedData[$key]['body'][$i]['no_sc'] = $i + 1;
                    }

                }

            }

            $yearString = Carbon::parse($this->month)->format('Y');
            $monthString = Carbon::parse($this->month)->format('F');
            $store = Store::find($store);

            $fileName = 'daily_report_' . $store->name . '_' . $monthString . '_' . $yearString . '.xlsx';

            // export to excel
            return (new DailyReport($exportedData))->download($fileName);
        }

        return;
    }
}
