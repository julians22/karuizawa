<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Class UsersTable.
 */
class UsersTable extends DataTableComponent
{
    /**
     * @var
     */
    public $status;

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
     * @var array|string[]
     */
    public array $sortNames = [
        'email_verified_at' => 'Verified',
        'two_factor_auth_count' => '2FA',
    ];

    /**
     * @var array|string[]
     */
    public array $filterNames = [
        'type' => 'User Type',
        'verified' => 'E-mail Verified',
    ];

    /**
     * @param  string  $status
     */
    public function mount($status = 'active'): void
    {
        $this->status = $status;
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        $query = User::with('roles', 'twoFactorAuth')->withCount('twoFactorAuth');

        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        } elseif ($this->status === 'deactivated') {
            $query = $query->onlyDeactivated();
        } else {
            $query = $query->onlyActive();
        }

        return $query;
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            'type' => SelectFilter::make('User Type')
                ->options([
                    '' => 'Any',
                    User::TYPE_ADMIN => 'Administrators',
                    User::TYPE_USER => 'Users',
                ])
                ->filter(function(Builder $query, $type) {
                    return $query->where('type', $type);
                }),
            'active' => SelectFilter::make('Active')
                ->options([
                    '' => 'Any',
                    'yes' => 'Yes',
                    'no' => 'No',
                ])
                ->filter(function(Builder $query, $active) {
                    return $query->where('active', $active === 'yes');
                }),
            'verified' => SelectFilter::make('E-mail Verified')
                ->options([
                    '' => 'Any',
                    'yes' => 'Yes',
                    'no' => 'No',
                ])
                ->filter(function(Builder $query, $verified) {
                    return $verified === 'yes' ? $query->whereNotNull('email_verified_at') : $query->whereNull('email_verified_at');
                }),
        ];
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('Type'))
                ->label(
                    fn($row, Column $column) => view('backend.auth.user.includes.type', ['user' => $row])
                )
                ->searchable()
                ->sortable(),
            Column::make(__('Store'), 'store.name')
                ->searchable()
                ->sortable(),
            Column::make(__('Name'), 'name')
                ->searchable()
                ->sortable(),
            Column::make(__('E-mail'), 'email')
                ->searchable()
                ->sortable(),
            Column::make(__('Verified'), 'email_verified_at')
                ->label(
                    fn($row, Column $column) => view('backend.auth.user.includes.verified', ['user' => $row])
                )
                ->sortable(),
            Column::make(__('2FA'))
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('two_factor_auth_count', $direction))
                ->label(
                    fn($row, Column $column) => view('backend.auth.user.includes.2fa', ['user' => $row])
                )
                ->html(),
            Column::make(__('Roles'))
                ->label(
                    fn($row, Column $column) => $row->roles_label
                ),
            Column::make(__('Additional Permissions'))
                ->label(
                    fn($row, Column $column) => $row->permissions_label
                ),
            Column::make(__('Actions'), 'id')
                ->format(fn($value, $row, Column $column) => view('backend.auth.user.includes.actions', ['user' => $row])),
        ];
    }

    /**
     * @return string
     */
    public function rowView(): string
    {
        return 'backend.auth.user.includes.row';
    }
}
