<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GroupCategoryExport implements WithMultipleSheets
{

    use Exportable;

    protected $groupCategories;
    protected $configures;
    protected $dataRaw;

    public function __construct($groupCategories, $configures, $data) {
        $this->groupCategories = $groupCategories;
        $this->configures = $configures;

        $this->dataRaw = $data;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->dataRaw as $key => $data) {

            $type = 'RTW';

            if ($key == 'Semi Custom') {
                $type = 'SEMI CUSTOM';
            }

            $sheets[] = new SummaryGroupCategoryExportSheet(collect($data), $key, $type);
        }

        $sheets[] = new GroupCategoryExportSheet(collect($this->groupCategories), $this->configures);

        return $sheets;
    }
}
