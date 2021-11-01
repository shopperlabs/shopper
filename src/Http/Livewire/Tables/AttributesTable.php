<?php

namespace Shopper\Framework\Http\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Shopper\Framework\Models\Shop\Product\Attribute;

class AttributesTable extends DataTableComponent
{
    public string $defaultSortColumn = 'name';

    public $columnSearch = [
        'name' => null,
        'type' => null,
    ];

    public array $bulkActions = [
        'deleteSelected' => 'Delete',
        'enabled'   => 'Enable',
        'disabled' => 'Disable',
    ];

    public function boot()
    {
        $this->queryString['columnSearch'] = ['except' => null];
    }

    public function deleteSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            Attribute::whereIn('id', $this->selectedKeys())->delete();

            $this->notify([
                'title' => __('Deleted'),
                'message' => __('The attribute has successfully removed!'),
            ]);
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function enabled(): void
    {
        if ($this->selectedRowsQuery->count() > 0) {
            Attribute::whereIn('id', $this->selectedKeys())->update(['is_enabled' => true]);

            $this->notify([
                'title' => __('Updated'),
                'message' => __('The attributes has successfully enabled!'),
            ]);
        }

        $this->selected = [];

        $this->resetBulk();
    }

    public function disabled(): void
    {
        if ($this->selectedRowsQuery->count() > 0) {
            Attribute::whereIn('id', $this->selectedKeys())->update(['is_enabled' => false]);

            $this->notify([
                'title' => __('Updated'),
                'message' => __('The attributes has successfully disabled!'),
            ]);
        }

        $this->resetBulk();
    }

    public function filters(): array
    {
        return [
            'is_searchable' => Filter::make('Is Searchable')
                ->select([
                    '' => __('Any'),
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ]),
            'is_filterable' => Filter::make('Is Filterable')
                ->select([
                    '' => __('Any'),
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ]),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('Name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $column, $row) {
                    return view('shopper::livewire.tables.cells.attributes.name')->with('attribute', $row);
                }),
            Column::make('Type')
                ->sortable()
                ->searchable()
                ->format(function ($value, $column, $row) {
                    return $row->type_formatted;
                }),
            Column::make('Is Searchable', 'is_searchable')
                ->sortable()
                ->addClass('text-gray-500 dark:text-gray-400')
                ->format(function ($value) {
                    return $value ? __('Yes') : __('No');
                }),
            Column::make('Is Filterable', 'is_filterable')
                ->sortable()
                ->format(function ($value) {
                    return $value ? __('Yes') : __('No');
                }),
            Column::make('Updated At', 'updated_at')
                ->sortable()
                ->addClass('hidden md:table-cell')
                ->format(function ($value) {
                    return $value ? "<time datetime='". $value->format('Y-m-d') . "' class='capitalize text-gray-500 dark:text-gray-400'>". $value->formatLocalized('%d %B, %Y') ."</time>": '';
                })->asHtml(),
        ];
    }

    public function query(): Builder
    {
        return Attribute::query()
            ->when($this->getFilter('is_searchable'), fn($query, $active) => $query->where('is_searchable', $active === 'yes'))
            ->when($this->getFilter('is_searchable'), fn($query, $active) => $query->where('is_searchable', $active === 'yes'));
    }
}
