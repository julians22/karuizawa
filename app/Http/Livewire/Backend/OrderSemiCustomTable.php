<?php

namespace App\Http\Livewire\Backend;

use App\Models\Order;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\OrderItem;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class OrderSemiCustomTable extends DataTableComponent
{

    public $stores = [
        '' => 'All',
    ];

    function mount(){
        $stores = Store::all();

        foreach ($stores as $store) {
            $this->stores[$store->id] = $store->code;
        }
    }

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
        $query = OrderItem::with(['order'])
            ->whereHas('order', function ($query) {
                return $query->where('status', config('enums.order_status.completed'));
            })
            ->semiCustom();
        return $query;
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Store')
                ->options(
                    $this->stores
                )
                ->filter(fn($builder, $value) => $builder->where('order.store_id', $value)),
            DateFilter::make('Order Date')
                ->filter(fn($builder, $value) => $builder->whereDate('order.order_date', $value)),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Order id")
                ->label(fn($row) => $row->product_sc->order_number)
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
            Column::make("Handling Date", "product_sc.handling_date")
                ->sortable()
                ->format(fn($value) => $value),
            Column::make("Status", "product_sc.status")
                ->sortable()
                ->format(fn($value) => $value == 'processing' ? '<span class="badge text-bg-info">Processing</span>' : '<span class="badge text-bg-success">Finish</span>')
                ->html(),
            Column::make("Created at", "created_at")
                ->sortable()
                ->format(fn($value) => $value->diffForHumans()),
        ];
    }
}
