<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
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
        return Customer::query()
            ->where('email', '!=', 'user@user.com')
            ->withCount([
                'orders as total_orders' => function (Builder $query) {
                    $query->where('status', 'completed');
                },
                'orders as count_semi_custom_products' => function (Builder $query) {
                    $query->where('status', 'completed')
                        ->whereHas('orderItems', function (Builder $orderItemQuery) {
                            $orderItemQuery->where('product_type', 'App\Models\SemiCustomProduct');
                        });
                },
                'orders as count_semi_custom_outer_products' => function (Builder $query) {
                    $query->where('status', 'completed')
                        ->whereHas('orderItems', function (Builder $orderItemQuery) {
                            $orderItemQuery->where('product_type', 'App\Models\SemiCustomOuterProduct');
                        });
                },
                'orders as count_rtw_products' => function (Builder $query) {
                    $query->where('status', 'completed')
                        ->whereHas('orderItems', function (Builder $orderItemQuery) {
                            $orderItemQuery->where('product_type', 'App\Models\Product');
                        });
                },
            ])
            ->withSum([
                'orders as spent_semi_custom_products' => function (Builder $query) {
                    $query->where('status', 'completed')
                        ->whereHas('orderItems', function (Builder $orderItemQuery) {
                            $orderItemQuery->where('product_type', 'App\Models\SemiCustomProduct');
                        });
                },
                'orders as spent_semi_custom_outer_products' => function (Builder $query) {
                    $query->where('status', 'completed')
                        ->whereHas('orderItems', function (Builder $orderItemQuery) {
                            $orderItemQuery->where('product_type', 'App\Models\SemiCustomOuterProduct');
                        });
                },
                'orders as spent_rtw_products' => function (Builder $query) {
                    $query->where('status', 'completed')
                        ->whereHas('orderItems', function (Builder $orderItemQuery) {
                            $orderItemQuery->where('product_type', 'App\Models\Product');
                        });
                },
            ], 'total_price')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Gender',
            'Address',
            'Total Spent',
            'Spend Semi-Custom Products',
            'Count Semi-Custom Products',
            'Spend Semi-Custom Outer Products',
            'Count Semi-Custom Outer Products',
            'Spend RTW Products',
            'Count RTW Products',
            'Total Orders',
            'Created At',
            'Updated At',
        ];
    }

    public function map($customer): array
    {
        $spentSemiCustomProducts = (float) ($customer->spent_semi_custom_products ?? 0);
        $spentSemiCustomOuterProducts = (float) ($customer->spent_semi_custom_outer_products ?? 0);
        $spentRTWProducts = (float) ($customer->spent_rtw_products ?? 0);

        return [
            $customer->full_name,
            $customer->email,
            $customer->phone,
            $customer->gender,
            $customer->address,
            $spentSemiCustomProducts + $spentSemiCustomOuterProducts + $spentRTWProducts,
            $spentSemiCustomProducts,
            (int) ($customer->count_semi_custom_products ?? 0),
            $spentSemiCustomOuterProducts,
            (int) ($customer->count_semi_custom_outer_products ?? 0),
            $spentRTWProducts,
            (int) ($customer->count_rtw_products ?? 0),
            (int) ($customer->total_orders ?? 0),
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
