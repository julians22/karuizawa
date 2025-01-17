<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Store;

class StoreTable extends DataTableComponent
{
    protected $model = Store::class;

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

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->deselected()
                ->sortable(),
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Code", "code")
                ->sortable(),
            Column::make('Accurate Alias', 'accurate_alias')
                ->sortable(),
            Column::make("Address", "address"),
            Column::make("Action")
                ->label(function ($row, Column $column) {
                    return view('backend.store.includes.actions', ['store' => $row]);
                }),
        ];
    }
}
