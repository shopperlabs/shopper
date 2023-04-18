<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Tables;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views;
use Shopper\Framework\Exceptions\GeneralException;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class ProductsTable extends DataTableComponent
{
    public $columnSearch = [
        'name' => null,
        'price_amount' => null,
    ];

    public function boot(): void
    {
        $this->queryString['columnSearch'] = ['except' => null];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('key')
            ->setAdditionalSelects(['is_visible'])
            ->setTdAttributes(function (Views\Column $column) {
                if ($column->isField('name')) {
                    return [
                        'class' => 'w-full max-w-md whitespace-nowrap',
                    ];
                }

                return [];
            })
            ->setBulkActions([
                'delete' => __('shopper::layout.forms.actions.delete'),
                'activate' => __('shopper::layout.forms.actions.activate'),
                'deactivate' => __('shopper::layout.forms.actions.disabled'),
            ]);
    }

    /**
     * @throws GeneralException
     */
    public function delete(): void
    {
        if (count($this->getSelected()) > 0) {
            (new ProductRepository())
                ->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->delete();

            Notification::make()
                ->title(__('shopper::components.tables.status.delete'))
                ->body(__('shopper::components.tables.messages.delete', ['name' => Str::plural('product', count($this->getSelected()))]))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    /**
     * @throws GeneralException
     */
    public function deactivate(): void
    {
        $this->setVisibility(false);
    }

    /**
     * @throws GeneralException
     */
    public function activate(): void
    {
        $this->setVisibility();
    }

    /**
     * @throws GeneralException
     */
    public function setVisibility(bool $status = true): void
    {
        if (count($this->getSelected()) > 0) {
            (new ProductRepository())
                ->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->update(['is_visible' => $status]);

            Notification::make()
                ->title(__('shopper::components.tables.status.visibility'))
                ->body(__('shopper::components.tables.messages.visibility', ['name' => Str::plural('product', count($this->getSelected()))]))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    public function filters(): array
    {
        return [
            'is_visible' => Views\Filters\SelectFilter::make(__('shopper::layout.forms.label.visible'))
                ->options([
                    '' => __('shopper::layout.forms.label.any'),
                    'yes' => __('shopper::layout.forms.label.yes'),
                    'no' => __('shopper::layout.forms.label.no'),
                ])
                ->filter(fn (Builder $query, $value) => $query->where('is_visible', $value === 'yes')),
            'brands' => Views\Filters\MultiSelectFilter::make(__('shopper::layout.sidebar.brands'))
                ->options(
                    (new BrandRepository())->makeModel()->newQuery()
                        ->orderBy('name')
                        ->get()
                        ->keyBy('id')
                        ->map(fn ($brand) => $brand->name)
                        ->toArray()
                )
                ->filter(
                    fn (Builder $query, array $brands) => $query->whereHas(
                        'brand',
                        fn (Builder $query) => $query->whereIn('brand_id', $brands)
                    )
                ),
        ];
    }

    public function columns(): array
    {
        return [
            Views\Column::make(__('shopper::layout.forms.label.name'), 'name')
                ->excludeFromColumnSelect()
                ->searchable()
                ->sortable()
                ->view('shopper::livewire.tables.cells.products.name'),
            Views\Column::make(__('shopper::layout.forms.label.price'), 'price_amount')
                ->sortable()
                ->searchable()
                ->format(function ($value) {
                    return $value ? '<span class="font-medium text-secondary-500 dark:text-secondary-400">' . shopper_money_format($value) . '</span>' : null;
                })->html(),
            Views\Column::make(__('shopper::layout.tables.sku'), 'sku')
                ->sortable()
                ->format(function ($value) {
                    return $value ? '<span class="font-medium text-secondary-500 dark:text-secondary-400">' . $value . '</span>' : '<span class="inline-flex text-secondary-700 dark:text-secondary-500">&mdash;</span>';
                })->html(),
            Views\Column::make(__('shopper::layout.forms.label.brand'), 'brand.name')
                ->view('shopper::livewire.tables.cells.products.brand'),
            Views\Column::make(__('shopper::layout.tables.stock'), 'security_stock')
                ->view('shopper::livewire.tables.cells.products.stock'),
            Views\Column::make(__('shopper::layout.forms.label.published_at'), 'published_at')
                ->view('shopper::livewire.tables.cells.date'),
        ];
    }

    /**
     * @throws GeneralException
     */
    public function builder(): Builder
    {
        return (new ProductRepository())
            ->makeModel()
            ->newQuery()
            ->with(['brand', 'variations', 'media'])
            ->withCount(['variations'])
            ->where('parent_id', null);
    }
}
