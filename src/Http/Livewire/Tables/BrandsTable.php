<?php

namespace Shopper\Framework\Http\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use WireUi\Traits\Actions;

class BrandsTable extends DataTableComponent
{
    use Actions;

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

    public function columns(): array
    {
        return [
            Column::make('Name')
                ->sortable()
                ->searchable(),
            Column::make('Website')->sortable(),
            Column::make('Url', 'slug')->sortable(),
            Column::make('Updated At', 'updated_at')
                ->sortable()
                ->addClass('hidden md:table-cell'),
        ];
    }

    public function deleteSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new BrandRepository())->makeModel()->newQuery()->whereIn('id', $this->selectedKeys())->delete();

            $this->notification()->success(__('Deleted'), __('The brands has successfully removed!'));
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function enabled(): void
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new BrandRepository())->makeModel()->newQuery()->whereIn('id', $this->selectedKeys())->update(['is_enabled' => true]);

            $this->notification()->success(__('Updated'), __('The brands has successfully enabled!'));
        }

        $this->selected = [];

        $this->resetBulk();
    }

    public function disabled(): void
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new BrandRepository())->makeModel()->newQuery()->whereIn('id', $this->selectedKeys())->update(['is_enabled' => false]);

            $this->notification()->success(__('Updated'), __('The brands has successfully disabled!'));
        }

        $this->resetBulk();
    }

    public function query(): Builder
    {
        return (new BrandRepository())->makeModel()->newQuery()
            ->when($this->columnSearch['name'] ?? null, fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'));
    }

    public function rowView(): string
    {
        return 'shopper::livewire.tables.rows.brands-table';
    }
}
