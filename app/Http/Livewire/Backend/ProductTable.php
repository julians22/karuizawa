<?php

namespace App\Http\Livewire\Backend;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ProductTable extends DataTableComponent
{
    public $categories = [];

    public function mount()
    {
        $categories = Product::query()
            ->select('category_id')
            ->with('category')
            ->groupBy('category_id')
            ->get()
            ->pluck('category.name', 'category_id')
            ->toArray();

        // push the first option
        $this->categories = ['' => 'All Categories'] + $categories;
    }

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

        $this->setDefaultSort('products.updated_at', 'desc');
        $this->setDefaultSortingLabels('Asc', 'Desc');
    }

    function builder(): Builder
    {
        $query = Product::with('productActualStocks', 'productActualStocks.store')
            ->withSum('productActualStocks as total_stock', 'stock_quantity');

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
            Column::make("SKU", "sku")
                ->searchable()
                ->sortable(),
            Column::make("Product name", "product_name")
                ->searchable()
                ->sortable(),
            Column::make("Current Stock")
                ->label(function($row, Column $column) {
                    return $row->total_stock ?? 0;
                })
                ->sortable(
                    function (Builder $query, $direction) {
                        return $query->orderBy('total_stock', $direction);
                    }
                ),
            Column::make("Stock List")
                ->label(fn($row, Column $column) => view('backend.product.includes.stock-list', ['product' => $row])),
            Column::make("Description", "description")
                ->sortable(),
            Column::make("Price", "price")
                ->sortable()
                ->format(function($value) {
                    return price_format($value);
                }),
            Column::make("Created at", "created_at")
                ->sortable()
                ->format(function($value) {
                    return $value->diffForHumans();
                }),
            Column::make("Last Update", "updated_at")
                ->sortable()
                ->format(function($value) {
                    return $value->diffForHumans();
                }),
            Column::make(__('Actions'))
                ->label(fn($row, Column $column) => view('backend.product.includes.actions', ['product' => $row])),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Category')
                ->options($this->categories)
                ->setFirstOption('All Categories')
                ->filter(function(Builder $builder, $value) {
                    $builder->where('category_id', $value);
                }),

            SelectFilter::make('Stock Warehouse')
                ->options([
                    '' => 'All Warehouse',
                    '1' => 'Ashta Mall',
                    '3' => 'PIK'
                ])
                ->filter(function(Builder $builder, $value) {
                    $builder->whereHas('productActualStocks', function($query) use ($value) {
                        $query->where('store_id', $value);
                    });
                }),
        ];
    }
}
