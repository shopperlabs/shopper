<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Tables;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views;
use Shopper\Framework\Exceptions\GeneralException;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class CollectionsTable extends DataTableComponent
{
    public $columnSearch = [
        'name' => null,
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['id', 'slug', 'sort'])
            ->setDefaultSort('name')
            ->setBulkActions([
                'deleteSelected' => __('shopper::layout.forms.actions.delete'),
            ]);
    }

    /**
     * @throws GeneralException
     */
    public function deleteSelected(): void
    {
        if (count($this->getSelected()) > 0) {
            (new CollectionRepository())
                ->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->delete();

            Notification::make()
                ->title(__('shopper::components.tables.status.delete'))
                ->body(__('The attribute has successfully disabled!'))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    public function filters(): array
    {
        return [
            'type' => Views\Filters\SelectFilter::make(__('shopper::pages/collections.filter_type'))
                ->options([
                    '' => __('shopper::layout.forms.label.any'),
                    'auto' => __('shopper::pages/collections.automatic'),
                    'manual' => __('shopper::pages/collections.manual'),
                ])
                ->filter(fn (Builder $query, string $type) => $query->where('type', $type)),
        ];
    }

    public function columns(): array
    {
        return [
            Views\Column::make(__('shopper::layout.forms.label.name'), 'name')
                ->sortable()
                ->searchable()
                ->view('shopper::livewire.tables.cells.collections.name'),
            Views\Column::make(__('shopper::layout.forms.label.type'), 'type')
                ->view('shopper::livewire.tables.cells.collections.type'),
            Views\Column::make(__('shopper::pages/collections.product_conditions'), 'match_conditions')
                ->view('shopper::livewire.tables.cells.collections.conditions'),
            Views\Column::make(__('shopper::layout.forms.label.updated_at'), 'updated_at')
                ->view('shopper::livewire.tables.cells.date'),
        ];
    }

    /**
     * @throws GeneralException
     */
    public function builder(): Builder
    {
        return (new CollectionRepository())
            ->makeModel()
            ->newQuery()
            ->with('rules')
            ->when($this->columnSearch['name'] ?? null, fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'));
    }
}
