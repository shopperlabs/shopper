<?php

namespace Shopper\Framework\Http\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class CategoriesTable extends DataTableComponent
{
    public string $defaultSortColumn = 'name';

    public $columnSearch = [
        'name' => null,
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
            (new CategoryRepository())->makeModel()->newQuery()->whereIn('id', $this->selectedKeys())->delete();

            $this->notify([
                'title' => __('Deleted'),
                'message' => __('The categories has successfully removed!'),
            ]);
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function enabled(): void
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new CategoryRepository())->makeModel()->newQuery()->whereIn('id', $this->selectedKeys())->update(['is_enabled' => true]);

            $this->notify([
                'title' => __('Updated'),
                'message' => __('The categories has successfully enabled!'),
            ]);
        }

        $this->selected = [];

        $this->resetBulk();
    }

    public function disabled(): void
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new CategoryRepository())->makeModel()->newQuery()->whereIn('id', $this->selectedKeys())->update(['is_enabled' => false]);

            $this->notify([
                'title' => __('Updated'),
                'message' => __('The categories has successfully disabled!'),
            ]);
        }

        $this->resetBulk();
    }

    public function columns(): array
    {
        return [
            Column::make('Name')
                ->sortable()
                ->searchable(),
            Column::make('Url', 'slug')->sortable(),
            Column::make('Updated At', 'updated_at')
                ->sortable(),
        ];
    }

    public function query(): Builder
    {
        return (new CategoryRepository())->makeModel()->newQuery()
            ->when($this->columnSearch['name'] ?? null, fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'));
    }

    public function rowView(): string
    {
        return 'shopper::livewire.tables.rows.categories-table';
    }
}
