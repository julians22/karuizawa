<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class RolesTable.
 */
class RolesTable extends DataTableComponent
{

    /**
     * Configure
     */
    public function configure(): void
    {
        $this->setPerPage(10);
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

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return Role::with('permissions:id,name,description')
            ->withCount('users');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Type'))
                ->label(
                    function ($row) {
                        if ($row->type === User::TYPE_ADMIN) {
                            return __('Administrator');
                        }

                        if ($row->type === User::TYPE_USER) {
                            return __('User');
                        }

                        return __('Unknown');
                    }
                ),
            Column::make(__('Name'))
                ->sortable(),
            Column::make(__('Permissions'))
                ->label(function (Role $role) {
                    return $role->permissions_label;
                }),
            Column::make(__('Number of Users'))
                ->label(function (Role $role) {
                    return $role->users_count;
                })
                ->sortable(),
            Column::make(__('Actions'))
                ->label(fn($row, Column $column) => view('backend.auth.role.includes.actions', ['model' => $row])),
        ];
    }

    public function rowView(): string
    {
        return 'backend.auth.role.includes.row';
    }
}
