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
            ->with([
                'product_sc',
                'product_sco',
            ])
            ->whereIn('product_type', [OrderItem::PRODUCT_TYPE_SC, OrderItem::PRODUCT_TYPE_SCO]);

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
            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    'processing' => 'Processing',
                    'finish' => 'Finish',
                ])
                ->filter(fn($builder, $value) => $builder->where('product_sc.status', $value)),
            DateFilter::make('Order Date')
                ->filter(fn($builder, $value) => $builder->whereDate('order.order_date', $value)),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('Transaction No', 'order.order_number')
                ->sortable()
                ->searchable(),
            Column::make("SC Order id")
                ->label(function($row){
                    if ($row->type == 'SC') {
                        return $row->product_sc->order_number ?? '-';
                    } else if ($row->type == 'SCO') {
                        return $row->product_sco->order_number ?? '-';
                    } else {
                        return '-';
                    }
                })
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
            Column::make("Product Name")
                ->label(function($row){
                    if ($row->type == 'SC') {
                        return $row->product_sc->name ?? '-';
                    } else if ($row->type == 'SCO') {
                        return $row->product_sco->name ?? '-';
                    } else {
                        return $row->product_rtw->name ?? '-';
                    }
                })
                ->sortable(function($query, $direction) {
                    $query->orderBy('product_sc.name', $direction)
                        ->orderBy('product_sco.name', $direction);
                })
                ->searchable(function($query, $searchTerm) {
                    $query->whereHas('product_sc', function($q) use ($searchTerm) {
                        $q->where('name', 'like', "%$searchTerm%");
                    })
                    ->orWhereHas('product_sco', function($q) use ($searchTerm) {
                        $q->where('name', 'like', "%$searchTerm%");
                    });
                }),
            Column::make("QTY", "quantity")
                ->sortable(),
            Column::make("(@) Price", "price")
                ->sortable()
                ->format(fn($value) => price_format($value)),
            Column::make("Order Date", "order.order_date")
                ->sortable()
                ->format(fn($value) => $value),
            Column::make("Handling Date")
                ->label(function($row){
                    if ($row->type == 'SC') {
                        return $row->product_sc->handling_date ?? '-';
                    } else if ($row->type == 'SCO') {
                        return $row->product_sco->handling_date ?? '-';
                    } else {
                        return '-';
                    }
                })
                ->sortable()
                ->format(fn($value) => $value),
            Column::make("Status", "product_sc.status")
                ->sortable()
                ->format(function($value, $row) {
                    if ($row->type == 'SC') {
                        return $value == 'processing' ? '<span class="text-bg-info badge">Processing</span>' : '<span class="text-bg-success badge">Finish</span>';
                    } else if ($row->type == 'SCO') {
                        return $row->product_sco->status == 'processing' ? '<span class="text-bg-info badge">Processing</span>' : '<span class="text-bg-success badge">Finish</span>';
                    } else {
                        return '-';
                    }
                })
                ->html(),
            Column::make("Created at", "created_at")
                ->sortable()
                ->format(fn($value) => $value->diffForHumans()),
            Column::make('Actions')
                ->label(fn($row) => view('backend.order.semi-custom.includes.actions', ['orderitem' => $row]))
        ];
    }
}
