<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class GroupCategoryExport implements FromCollection
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

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new GroupCategoryExportSheet(collect($this->groupCategories), $this->configures);

        return $sheets;
    }
}
