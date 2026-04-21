<?php

namespace App\Http\Livewire\Backend;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

class CustomerTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder() : Builder
    {
        return Customer::query()->withCount(['orders' => function ($query) {
            $query->where('status', config('enums.order_status.completed'));
        }]);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->deselected()
                ->sortable(),
            Column::make("Full name", "full_name")
                ->searchable()
                ->sortable(),
            Column::make("Phone", "phone")
                ->searchable()
                ->sortable(),
            Column::make("Email", "email")
                ->searchable()
                ->sortable(),
            Column::make("Total Orders")
                ->label(fn ($row) => view('backend.customer.includes.total_orders', ['row' => $row]))
                ->sortable(fn ($builder, $direction) => $builder->orderBy('orders_count', $direction)),
            Column::make("Created at", "created_at")
                ->format(fn ($value) => $value->format('Y-m-d H:i'))
                ->sortable(),
        ];
    }
}
