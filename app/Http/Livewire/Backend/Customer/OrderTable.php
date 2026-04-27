<?php

namespace App\Http\Livewire\Backend\Customer;

use App\Models\Customer;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Order;

class OrderTable extends DataTableComponent
{
    public Customer $customer;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setSingleSortingDisabled();
        $this->setDefaultSort('order_date', 'desc');

        $this->addAdditionalSelects('status');
    }

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function builder() : \Illuminate\Database\Eloquent\Builder
    {
        return Order::query()
            ->with(['store', 'orderItems'])
            ->where('customer_id', $this->customer->id);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->deselected()
                ->sortable(),
            Column::make("Store", "store.name")
                ->sortable(),
            Column::make("Total price", "total_price")
                ->sortable()
                ->format(fn($value) => price_format($value)),
            Column::make("Status", "status")
                ->sortable()
                ->label(
                    function($row, Column $column){
                        switch ($row->status) {
                            case 'pending':
                                return '<span class="bg-warning badge">Pending</span>';
                            case 'completed':
                                return '<span class="bg-success badge">Completed</span>';
                            case 'cancelled':
                                return '<span class="bg-danger badge">Canceled</span>';
                            default:
                                return '<span class="bg-secondary badge">Unknown</span>';
                        }
                    }
                )
                ->html(),
            Column::make("Order number", "order_number")
                ->sortable(),
            Column::make("Order date", "order_date")
                ->sortable()
        ];
    }
}
