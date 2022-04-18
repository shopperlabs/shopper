<?php

namespace Shopper\Framework\Http\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use WireUi\Traits\Actions;

class CollectionsTable extends DataTableComponent
{
    use Actions;

    public string $defaultSortColumn = 'name';

    public $columnSearch = [
        'name' => null,
    ];

    public array $bulkActions = [
        'deleteSelected' => 'Delete',
    ];

    public array $filterNames = [
        'type' => 'Type',
    ];

    public function deleteSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new CollectionRepository())->makeModel()->newQuery()->whereIn('id', $this->selectedKeys())->delete();

            $this->notification()->success(
                __('shopper::components.tables.status.delete'),
                __('shopper::components.tables.messages.delete', ['name' => 'collections'])
            );
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function filters(): array
    {
        return [
            'type' => Filter::make(__('shopper::pages/collections.filter_type'))
                ->select([
                    '' => __('shopper::layout.forms.label.any'),
                    'auto' => __('shopper::pages/collections.automatic'),
                    'manual' => __('shopper::pages/collections.manual'),
                ]),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make(__('shopper::layout.forms.label.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('shopper::layout.forms.label.type'), 'type')->sortable(),
            Column::make(__('shopper::pages/collections.product_conditions')),
            Column::make(__('shopper::layout.forms.label.updated_at'), 'updated_at')
                ->sortable(),
        ];
    }

    public function query(): Builder
    {
        return (new CollectionRepository())->makeModel()->newQuery()
            ->with('rules')
            ->when($this->columnSearch['name'] ?? null, fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'))
            ->when($this->getFilter('type'), fn ($query, $type) => $query->where('type', $type));
    }

    public function rowView(): string
    {
        return 'shopper::livewire.tables.rows.collections-table';
    }
}
