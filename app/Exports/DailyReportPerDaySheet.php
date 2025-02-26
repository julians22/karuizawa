<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyReportPerDaySheet implements FromCollection, WithTitle, ShouldAutoSize, WithHeadings, WithColumnFormatting, WithCustomStartCell, WithStyles
{
    protected $orderItem;
    protected $date;

    public function __construct($orderItem, $date) {
        $this->orderItem = $orderItem;
        $this->date = $date;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->orderItem;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Artikel',
            'Type',
            'Qty',
            'Price',
            'Disc',
            'Total',
            'Service By:',
            'Pembayaran',
            '',
            '',
            'No',
            'No. Order',
            'Nama Customer',
            'No. HP',
            'Tgl. Penanganan',
            'Kode Fabric',
            'Nama Fabric',
            'Collar',
            'Cuff',
            'Front Body',
            'Pocket',
            'Back Body',
            'Button',
            'Type Customer (NO/RO/GS)',
            'Type Badan',
            'Sleeve',
            'Qty',
            'Price',
            'Discount',
            'Option',
            'Option Additional',
            'Gift Card',
            'Total',
            'Pembayaran'
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        $idrFormat = '_-Rp* #.##0_-;-Rp* #.##0_-;_-Rp* "-"_-;_-@';

        return [
            'E' => $idrFormat,
            'G' => $idrFormat,
            'AC' => $idrFormat,
            'AE' => $idrFormat,
            'AF' => $idrFormat,
            'AG' => $idrFormat,
            'AH' => $idrFormat,
        ];
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            3    => [
                'font' => ['bold' => true]
                ],

            // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }
}
