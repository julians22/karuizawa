<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GroupCategoryExport implements FromCollection, ShouldAutoSize, WithStyles
{

    use Exportable;

    protected $groupCategories;
    protected $configures;

    public function __construct($groupCategories, $configures) {
        $this->groupCategories = $groupCategories;
        $this->configures = $configures;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->groupCategories;
    }

    public function styles(Worksheet $sheet)
    {
        $headers = $this->configures['headers'];

        return $headers;
    }
}
