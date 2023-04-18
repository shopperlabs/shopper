<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Tables;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views;
use Shopper\Framework\Exceptions\GeneralException;
use Shopper\Framework\Repositories\UserRepository;

class CustomersTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['id', 'first_name', 'last_name', 'email', 'email_verified_at'])
            ->setDefaultSort('first_name')
            ->setBulkActions([
                'deleteSelected' => __('Delete'),
                'verified' => __('Verified'),
            ]);
    }

    public function columns(): array
    {
        return [
            Views\Column::make(__('shopper::layout.forms.label.full_name'), 'first_name')
                ->sortable()
                ->searchable()
                ->view('shopper::livewire.tables.cells.customers.name'),
            Views\Column::make(__('shopper::layout.forms.label.email_subscription'), 'opt_in')
                ->view('shopper::livewire.tables.cells.customers.opt-in'),
            Views\Column::make(__('shopper::layout.forms.label.registered_at'), 'created_at')
                ->view('shopper::livewire.tables.cells.date'),
        ];
    }

    /**
     * @throws GeneralException
     */
    public function verified(): void
    {
        if (count($this->getSelected()) > 0) {
            (new UserRepository())->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->update(['email_verified_at' => now()]);

            Notification::make()
                ->title(__('shopper::components.tables.status.verified'))
                ->body(__('shopper::components.tables.messages.verified', ['name' => __('customers')]))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->resetAll();
    }

    /**
     * @throws GeneralException
     */
    public function deleteSelected(): void
    {
        if (count($this->getSelected()) > 0) {
            (new UserRepository())->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->delete();

            Notification::make()
                ->title(__('shopper::components.tables.status.delete'))
                ->body(__('shopper::components.tables.messages.delete', ['name' => __('customers')]))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    public function filters(): array
    {
        return [
            'mailing' => Views\Filters\SelectFilter::make(__('shopper::layout.forms.label.email_subscription'))
                ->options([
                    '' => __('shopper::layout.forms.label.any'),
                    'yes' => __('shopper::layout.forms.label.yes'),
                    'no' => __('shopper::layout.forms.label.no'),
                ])
                ->filter(fn (Builder $query, string $value) => $query->where('opt_in', $value === 'yes')),
            'verified' => Views\Filters\SelectFilter::make(__('shopper::layout.forms.label.email_verified'))
                ->options([
                    '' => __('shopper::layout.forms.label.any'),
                    'yes' => __('shopper::layout.forms.label.yes'),
                    'no' => __('shopper::layout.forms.label.no'),
                ])
                ->filter(fn (Builder $query, $verified) => $verified === 'yes' ? $query->whereNotNull('email_verified_at') : $query->whereNull('email_verified_at')),
        ];
    }

    /**
     * @throws GeneralException
     */
    public function builder(): Builder
    {
        return (new UserRepository())->makeModel()->newQuery()
            ->whereHas('roles', fn (Builder $query) => $query->where('name', config('shopper.system.users.default_role')))
            ->when($this->getAppliedFilterWithValue('search'), fn (Builder $query, $term) => $query->research($term));
    }
}
