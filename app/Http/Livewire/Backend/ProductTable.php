<?php

namespace App\Http\Livewire\Backend;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductTable extends DataTableComponent
{

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTrAttributes(function ($row, $index) {
            if ($index % 2 === 0) {
                return [
                    'default' => false,
                    'class' => 'rappasoft-striped-row',
                ];
            }
            return [
                'default' => false,
                'class' => 'laravel-livewire-tables-odd rappasoft-striped-row',
            ];
        });
    }

    function builder(): Builder
    {
        $query = Product::with('stockMovements');

        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->deselected()
                ->sortable(),
            Column::make("Category", "category.name")
                ->searchable()
                ->sortable(),
            Column::make("SKU", "sku"),
            Column::make("Product name", "product_name")
                ->searchable()
                ->sortable(),
            Column::make("Stock")
                ->sortable()
                ->label(function ($row, Column $column) {
                    return $row->current_stock;
                }),
            Column::make("Description", "description")
                ->sortable(),
            Column::make("Price", "price")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make(__('Actions'))
                ->label(fn($row, Column $column) => view('backend.product.includes.actions', ['product' => $row])),
        ];
    }
}
