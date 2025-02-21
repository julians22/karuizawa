<?php

namespace App\Http\Livewire\Backend;

use App\Models\Order;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Builder;

class OrderSemiCustomTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setEagerLoadAllRelationsEnabled();

        $this->addAdditionalSelects(['product_type', 'product_id']);

        $this->setSingleSortingDisabled();
        $this->setDefaultSort('created_at', 'desc');
    }

    public function builder(): Builder
    {
        $query = OrderItem::with(['order' =>
            function($query){
                $query->with('store');
            }
        ])
            ->semiCustom()
            ->whereHas('order', function ($query) {
                $query->where('status', '!=', config('enums.order_status.completed'));
            });
        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make("Order id", "order.order_number")
                ->sortable(),
            Column::make("Store", "order.store.name")
                ->sortable()
                ->searchable(),
            Column::make("Crew", "order.user.name")
                ->sortable()
                ->searchable(),
            Column::make('Customer', 'order.customer.full_name')
                ->sortable()
                ->searchable(),
            Column::make("Product", "product_sc.name")
                ->sortable()
                ->searchable(),
            Column::make("QTY", "quantity")
                ->sortable(),
            Column::make("(@) Price", "price")
                ->sortable()
                ->format(fn($value) => price_format($value)),
            Column::make("Order Date", "order.order_date")
                ->sortable()
                ->format(fn($value) => $value),
            Column::make("Created at", "created_at")
                ->sortable()
                ->format(fn($value) => $value->diffForHumans()),
        ];
    }
}
