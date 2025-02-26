<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DailyReport implements WithMultipleSheets
{
    use Exportable;

    protected $orderItems;

    public function __construct($orderItems)
    {
        $this->orderItems = $orderItems;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->orderItems as $key => $orderItem) {
            $sheets[] = new DailyReportPerDaySheet(collect($orderItem['body']), $key);
        }

        return $sheets;
    }
}
