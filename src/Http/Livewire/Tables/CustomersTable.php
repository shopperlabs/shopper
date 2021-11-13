<?php

namespace Shopper\Framework\Http\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Shopper\Framework\Repositories\UserRepository;
use WireUi\Traits\Actions;

class CustomersTable extends DataTableComponent
{
    use Actions;

    public string $defaultSortColumn = 'first_name';

    public array $bulkActions = [
        'deleteSelected' => 'Delete',
        'verified' => 'Verified',
    ];

    public array $sortNames = [
        'email_verified_at' => 'Verified',
    ];

    public array $filterNames = [
        'verified' => 'E-mail Verified',
    ];

    public function columns(): array
    {
        return [
            Column::make('Full Name', 'first_name')
                ->sortable()
                ->searchable(),
            Column::make('Email Subscription', 'opt_in')->sortable(),
            Column::make('Registered At', 'created_at')
                ->addClass('text-right')
                ->sortable(),
        ];
    }

    public function verified()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new UserRepository())->makeModel()->newQuery()->whereIn('id', $this->selectedKeys())->update(['email_verified_at' => now()]);

            $this->notification()->success(__('Verified'), __('The users has successfully verified!'));
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function deleteSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new UserRepository())->makeModel()->newQuery()->whereIn('id', $this->selectedKeys())->delete();

            $this->notification()->success(__('Deleted'), __('The users has successfully removed!'));
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function filters(): array
    {
        return [
            'mailing' => Filter::make('E-mail Subscription')
                ->select([
                    '' => __('Any'),
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ]),
            'verified' => Filter::make('E-mail Verified')
                ->select([
                    '' => __('Any'),
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ]),
        ];
    }

    public function query(): Builder
    {
        return (new UserRepository())->makeModel()->newQuery()
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', config('shopper.system.users.default_role'));
            })
            ->when($this->getFilter('search'), fn ($query, $term) => $query->research($term))
            ->when($this->getFilter('mailing'), fn ($query, $active) => $query->where('opt_in', $active === 'yes'))
            ->when($this->getFilter('verified'), fn ($query, $verified) => $verified === 'yes' ? $query->whereNotNull('email_verified_at') : $query->whereNull('email_verified_at'));
    }

    public function rowView(): string
    {
        return 'shopper::livewire.tables.rows.customers-table';
    }
}
