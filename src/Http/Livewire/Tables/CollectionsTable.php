<?php

namespace Shopper\Framework\Http\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class CollectionsTable extends DataTableComponent
{
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

            $this->notify([
                'title' => __('Deleted'),
                'message' => __('The collections has successfully removed!'),
            ]);
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function filters(): array
    {
        return [
            'type' => Filter::make(__('Collection Type'))
                ->select([
                    '' => __('Any'),
                    'auto' => __('Automatic'),
                    'manual' => __('Manual'),
                ]),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make(__('Name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make('Type', 'slug')->sortable(),
            Column::make(__('Product Conditions')),
            Column::make(__('Updated At'), 'updated_at')
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
