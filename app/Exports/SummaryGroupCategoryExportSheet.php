<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SummaryGroupCategoryExportSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    protected string $type = 'RTW';
    protected $groupData;
    protected $category;

    public function __construct($groupData, $category, $type = 'RTW') {
        $this->groupData = $groupData;
        $this->category = $category;
        $this->type = $type;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $groupData = $this->groupData;

        $data = [];

        $data[] = new Column('Name', $this->type, ' ', 'Index');
        $data[] = new Column(' ', $this->category, ' ', ' ');
        $data[] = new Column(' ', 'Target', 'Actual', ' ');

        foreach ($groupData as $key => $value) {

            $crewName = $key;
            $target = 0;

            $actual = 0;

            foreach ($value as $date => $total) {
                $actual += $total;
            }

            $data[] = new Column($crewName, $target, $actual, ' ');
        }

        return collect($data);

    }

    public function title(): string
    {
        return $this->category;
    }

    public function styles(Worksheet $sheet) {

        $title = [
                'font' => [
                    'bold' => true,
                    'size' => 16
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ];

        return [
            1 => $title,
            2 => $title,
            3 => $title,
            'A' => $title,
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->mergeCells('A1:A3');
                if ($this->type == 'RTW') {
                    $event->sheet->mergeCells('B1:C1');
                    $event->sheet->mergeCells('B2:C2');
                } else {
                    $event->sheet->mergeCells('B1:C2');
                }
                $event->sheet->mergeCells('D1:D3');
            },

        ];
    }
}

class Column
{

    public $col1 = ' ';
    public $col2 = ' ';
    public $col3 = ' ';
    public $col4 = ' ';

    public function __construct($col1 = ' ', $col2 = ' ', $col3 = ' ', $col4 = ' ')
    {
        $this->col1 = $col1;
        $this->col2 = $col2;
        $this->col3 = $col3;
        $this->col4 = $col4;
    }

}

