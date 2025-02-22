<?php

namespace App\Http\Livewire\Backend;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Category;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class ProductCategoryTable extends DataTableComponent
{
    protected $model = Category::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("Slug", "slug")
                ->sortable(),
            Column::make("Last Update", "updated_at")
                ->sortable()
                ->format(fn($value) => $value->diffForHumans()),
            Column::make("Actions")
                ->label(fn($row) => view('backend.product-category.includes.actions', ['category' => $row]))
        ];
    }
}
