<?php

namespace App\Http\Livewire\Backend;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\OrderItem;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ArrayColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class OrderReadyToWearTable extends DataTableComponent
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
        $this->addAdditionalSelects(['product_type', 'product_id', 'discount_detail', 'order_items.discount']);

        $this->setSingleSortingDisabled();
        $this->setDefaultSort('created_at', 'desc');
    }

    public function builder(): Builder
    {
        $query = OrderItem::with(['order' =>
                function($query){
                    return $query->where('status', '!=', config('enums.order_status.completed'));
                }
            ])
            ->readyToWear();
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
            Column::make("Order id", "order.order_number")
                ->sortable(),
            Column::make("Store", "order.store.name")
                ->sortable()
                ->searchable(),
            Column::make("Crew", "order.user.name")
                ->sortable()
                ->searchable(),
            Column::make("SKU", "product_rtw.sku")
                ->sortable()
                ->searchable(),
            Column::make("Product", "product_rtw.product_name")
                ->sortable()
                ->searchable(),
            Column::make("QTY", "quantity")
                ->sortable(),
            Column::make("(@) Price", "price")
                ->sortable()
                ->format(fn($value) => price_format($value)),
            Column::make("Discount Applied", "discount")
                ->label(fn($row) => $row->discount_percentage ? $row->discount_percentage . '%' : 'No Discount'),
            Column::make("Total Price", "id")
                ->sortable()
                ->label(fn($row) => price_format($row->total_price)),
            Column::make("Order Date", "order.order_date")
                ->sortable()
                ->format(fn($value) => $value),
            Column::make("Created at", "created_at")
                ->sortable()
                ->format(fn($value) => $value->diffForHumans()),
        ];
    }
}
