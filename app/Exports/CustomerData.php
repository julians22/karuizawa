<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;

class CustomerData implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithStyles
{

    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Customer::where('email', '!=', 'user@user.com')->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Gender',
            'Address',
            'Created At',
            'Updated At',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->full_name,
            $customer->email,
            $customer->phone,
            $customer->gender,
            $customer->address,
            $customer->created_at,
            $customer->updated_at,
        ];
    }

    public function styles($sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FF0000']], 'alignment' => ['horizontal' => 'center']],
        ];
    }
}
