<?php

namespace App\Http\Livewire\Backend;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;

class ProductTable extends DataTableComponent
{
    protected $model = Product::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
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
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
