<?php

namespace App\Http\Livewire\Backend\OnlineSales;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class OrderTable extends DataTableComponent
{
    protected $model = Order::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSingleSortingDisabled();
        $this->setDefaultSort('created_at', 'desc');
        $this->addAdditionalSelects('orders.id', 'orders.status');
        $this->setSearchPlaceholder('Search by Order ID, Customer Name, Phone');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->filter(fn ($builder, $value) => $builder->where('orders.status', $value)),
            SelectFilter::make('Shipment Status')
                ->options([
                    '' => 'All',
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'allocated' => 'Allocated',
                    'picking_up' => 'Picking Up',
                    'picked' => 'Picked',
                    'dropping_off' => 'Dropping Off',
                    'delivered' => 'Delivered',
                    'returned' => 'Returned',
                    'cancelled' => 'Cancelled',
                    'courier_not_found' => 'Courier Not Found',
                ])
                ->filter(fn ($builder, $value) => $builder->whereHas('shipment', fn ($query) => $query->where('status', $value))),
            DateFilter::make('Order Date')
                ->filter(fn ($builder, $value) => $builder->whereDate('order_date', $value)),
        ];
    }

    public function builder(): Builder
    {
        return Order::query()
            ->select('orders.*')
            ->online()
            ->with(['shipment'])
            ->without('payments');
    }

    public function columns(): array
    {
        return [
            Column::make('Order Id', 'order_number')
                ->searchable()
                ->sortable(),
            Column::make('Customer', 'customer.full_name')
                ->searchable()
                ->sortable(),
            Column::make('Phone', 'customer.phone')
                ->searchable(),
            Column::make('Total price', 'total_price')
                ->sortable()
                ->format(fn ($value) => price_format($value)),
            Column::make('Courier', 'shipment.courier_company')
                ->format(fn ($value, $row) => $row->shipment
                    ? strtoupper($row->shipment->courier_company) . ' ' . ($row->shipment->courier_service_name ?? '')
                    : '-'),
            Column::make('Shipment Status', 'shipment.status')
                ->label(fn ($row) => $row->shipment
                    ? '<span class="bg-info badge">' . str($row->shipment->status)->headline() . '</span>'
                    : '<span class="bg-secondary badge">No Shipment</span>')
                ->html(),
            Column::make('Order Status', 'status')
                ->sortable()
                ->label(function ($row) {
                    return match ($row->status) {
                        'pending' => '<span class="bg-warning badge">Pending</span>',
                        'processing' => '<span class="bg-info badge">Processing</span>',
                        'completed' => '<span class="bg-success badge">Completed</span>',
                        'cancelled' => '<span class="bg-danger badge">Cancelled</span>',
                        default => '<span class="bg-secondary badge">Unknown</span>',
                    };
                })
                ->html(),
            Column::make('Order Date', 'order_date')
                ->sortable(),
            Column::make('Created at', 'created_at')
                ->format(fn ($value) => $value->diffForHumans())
                ->sortable(),
            Column::make('Actions')
                ->label(fn ($row) => view('backend.online-sales.order.includes.actions', ['order' => $row])),
        ];
    }
}
