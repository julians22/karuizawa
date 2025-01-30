<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\User;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Order;
use App\Models\Store;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class OrderTable extends DataTableComponent
{
    protected $model = Order::class;

    public $stores = [];

    function mount(){
        $stores = Store::all();

        foreach ($stores as $store) {
            $this->stores[$store->id] = $store->code;
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setBulkActions([
            'syncAccurate' => 'Sync to Accurate',
        ]);
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    'pending' => 'Pending',
                    // 'processing' => 'Processing',
                    'completed' => 'Completed',
                    // 'cancelled' => 'Cancelled',
                ])
                ->filter(fn($builder, $value) => $builder->where('status', $value)),
            SelectFilter::make('Store')
                ->options(
                    $this->stores
                )
                ->filter(fn($builder, $value) => $builder->where('orders.store_id', $value)),
            DateFilter::make('Order Date')
                ->filter(fn($builder, $value) => $builder->whereDate('order_date', $value)),
            SelectFilter::make('Sync Status')
                ->options([
                    'synced' => 'Synced',
                    'not_synced' => 'Not Synced',
                ])
                ->filter(fn($builder, $value) => $builder->where('accurate_order_number', $value === 'synced' ? '!=' : '=', null)),
            SelectFilter::make('Order Type')
                ->options([
                    'all' => 'All',
                    'sc' => 'Semi Custom',
                    'rtw' => 'Only Ready to Wear',
                ])
                ->filter(function($builder, $value){
                    if ($value === 'sc') {
                        return $builder->whereHas('orderItems', function($query){
                            $query->where('product_type', 'App\Models\SemiCustomProduct');
                        });
                    }

                    if ($value === 'rtw') {
                        return $builder->whereDoesntHave('orderItems', function($query){
                            $query->where('product_type', 'App\Models\SemiCustomProduct');
                        });
                    }
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Order Id", "order_number")
                ->deselected()
                ->sortable(),
            Column::make("Customer", "customer.full_name")
                ->sortable(),
            Column::make("Store", "store.code")
                ->sortable(),
            Column::make('Crew', 'user.name')
                ->sortable(),
            Column::make("Total price", "total_price")
                ->sortable()
                ->format(fn($value) => price_format($value)),
            Column::make("Status", "status")
                ->sortable(),
            BooleanColumn::make("Semi Custom?", "id")
                ->setCallback(function(string $value, $row) {
                    return $row->hasSemiCustom();
                }),
            Column::make('Accurate Info', 'accurate_order_number')
                ->format(fn($value, $row) => $value ? $value : "Not Synced"),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
            Column::make("Actions")
                ->label(fn($row, Column $column) => view('backend.order.includes.actions', ['order' => $row])),
        ];
    }

    function syncAccurate(){

        // check if order_date, status, is applied to the selected orders
        $filters = $this->getAppliedFiltersWithValues('sync_status');
        $syncStatus = $filters['sync_status'] ?? 'not_synced';

        if ($syncStatus === 'synced') {
            $this->dispatch('notify-feature');
        }
        $this->dispatch('notify-feature');
    }
}
