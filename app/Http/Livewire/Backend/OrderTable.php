<?php

namespace App\Http\Livewire\Backend;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Order;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class OrderTable extends DataTableComponent
{
    protected $model = Order::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->filter(fn($builder, $value) => $builder->where('status', $value)),
            SelectFilter::make('Store')
                ->options([
                    '1' => 'Ashta Mall',
                    '3' => 'PIK'
                ])
                ->filter(fn($builder, $value) => $builder->where('store_id', $value)),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Customer", "customer.full_name")
                ->sortable(),
            Column::make("Store", "store.code")
                ->sortable(),
            Column::make("Total price", "total_price")
                ->sortable()
                ->format(fn($value) => price_format($value)),
            Column::make("Status", "status")
                ->sortable(),
            Column::make("Semi Custom?")
                ->label(fn($row, Column $column) => $row->orderItems->where('product_type', 'App\Models\SemiCustomProduct')->count() > 0 ? 'Yes' : 'No'),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
            Column::make("Actions")
                ->label(fn($row, Column $column) => view('backend.order.includes.actions', ['order' => $row])),
        ];
    }
}
