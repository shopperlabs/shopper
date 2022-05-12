<?php

namespace Shopper\Framework\Http\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use WireUi\Traits\Actions;

class ProductsTable extends DataTableComponent
{
    use Actions;

    public bool $columnSelect = true;

    public $columnSearch = [
        'name' => null,
        'price_amount' => null,
    ];

    public function boot()
    {
        $this->queryString['columnSearch'] = ['except' => null];
    }

    public function bulkActions(): array
    {
        return [
            'delete'   => __('shopper::layout.forms.actions.delete'),
            'activate' => __('shopper::layout.forms.actions.activate'),
            'deactivate' => __('shopper::layout.forms.actions.disabled'),
        ];
    }

    public function delete()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new ProductRepository())->makeModel()
                ->newQuery()
                ->whereIn('id', $this->selectedKeys())
                ->delete();

            $this->notification()->success(
                __('shopper::components.tables.status.delete'),
                __('shopper::components.tables.messages.delete', ['name' => Str::plural('product', $this->selectedRowsQuery->count())])
            );
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function deactivate()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new ProductRepository())->makeModel()
                ->newQuery()
                ->whereIn('id', $this->selectedKeys())
                ->update(['is_visible' => false]);

            $this->notification()->success(
                __('shopper::components.tables.status.visibility'),
                __('shopper::components.tables.messages.visibility', ['name' => Str::plural('product', $this->selectedRowsQuery->count())])
            );
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function activate()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            (new ProductRepository())->makeModel()
                ->newQuery()
                ->whereIn('id', $this->selectedKeys())
                ->update(['is_visible' => true]);

            $this->notification()->success(
                __('shopper::components.tables.status.visibility'),
                __('shopper::components.tables.messages.visibility', ['name' => Str::plural('product', $this->selectedRowsQuery->count())])
            );
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function filters(): array
    {
        return [
            'is_visible' => Filter::make(__('shopper::layout.forms.label.visible'))
                ->select([
                    '' => __('shopper::layout.forms.label.any'),
                    'yes' => __('shopper::layout.forms.label.yes'),
                    'no' => __('shopper::layout.forms.label.no'),
                ]),
            'brands' => Filter::make(__('shopper::layout.sidebar.brands'))
                ->multiSelect(
                    Cache::remember('brands-filters', 60 * 30, function () {
                        return (new BrandRepository())->makeModel()->newQuery()
                            ->orderBy('name')
                            ->get()
                            ->keyBy('id')
                            ->map(fn ($name) => $name->name)
                            ->toArray();
                    })
                ),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make(__('shopper::layout.forms.label.name'))
                ->excludeFromSelectable()
                ->searchable()
                ->sortable()
                ->format(function ($value, $column, $row) {
                    return view('shopper::livewire.tables.cells.products.name')->with('product', $row);
                }),
            Column::make(__('shopper::layout.forms.label.price'), 'price_amount')
                ->sortable()
                ->searchable()
                ->format(function ($value) {
                    return $value ? '<span class="font-medium text-secondary-500 dark:text-secondary-400">' . shopper_money_format($value) . '</span>' : null;
                })->asHtml(),
            Column::make(__('shopper::layout.tables.sku'), 'sku')
                ->sortable()
                ->format(function ($value) {
                    return $value ? '<span class="font-medium text-secondary-500 dark:text-secondary-400">' . $value . '</span>' : '<span class="inline-flex text-secondary-700 dark:text-secondary-500">&mdash;</span>';
                })->asHtml(),
            Column::make(__('shopper::layout.forms.label.brand'), 'brand')
                ->format(function ($value) {
                    return view('shopper::livewire.tables.cells.products.brand')->with('brand', $value);
                }),
            Column::make(__('shopper::layout.tables.stock'))
                ->format(function ($value, $column, $row) {
                    return view('shopper::livewire.tables.cells.products.stock')->with('product', $row);
                }),
            Column::make(__('shopper::layout.forms.label.published_at'), 'published_at')
                ->sortable()
                ->format(function ($value) {
                    return $value ? "<time datetime='" . $value->format('Y-m-d') . "' class='capitalize text-secondary-500 dark:text-secondary-400'>" . $value->formatLocalized('%d %B, %Y') . '</time>' : '';
                })->asHtml(),
        ];
    }

    public function query(): Builder
    {
        return (new ProductRepository())->makeModel()->newQuery()->with(['brand', 'variations'])
            ->withCount(['variations'])
            ->where('parent_id', null)
            ->when($this->getFilter('brands'), fn ($query, $brands) => $query->whereHas('brand', fn ($query) => $query->whereIn('brand_id', $brands)))
            ->when($this->getFilter('is_visible'), fn ($query, $active) => $query->where('is_visible', $active === 'yes'));
    }
}
