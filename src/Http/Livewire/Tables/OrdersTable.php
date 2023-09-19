<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Tables;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\Shop\Order\OrderStatus;

class OrdersTable extends DataTableComponent
{
    public $columnSearch = [
        'number' => null,
    ];

    public function boot(): void
    {
        $this->queryString['columnSearch'] = ['except' => null];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['id', 'currency', 'shipping_total', 'shipping_method'])
            ->setFilterLayoutSlideDown()
            ->setTdAttributes(function (Views\Column $column) {
                if ($column->isField('id')) {
                    return [
                        'class' => 'w-full max-w-lg whitespace-nowrap truncate',
                    ];
                }
                if ($column->isField('price_amount')) {
                    return [
                        'class' => 'text-right',
                    ];
                }

                return [];
            })
            ->setBulkActions([
                'archived' => __('shopper::layout.forms.actions.archived'),
            ]);
    }

    public function archived(): void
    {
        if (count($this->getSelected()) > 0) {
            Order::query()->whereIn('id', $this->getSelected())->delete();

            Notification::make()
                ->title(__('shopper::layout.forms.actions.archived'))
                ->body(__('The orders has successfully archived!'))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    public function filters(): array
    {
        return [
            'status' => Views\Filters\SelectFilter::make(__('shopper::layout.forms.label.status'))
                ->options(array_merge(
                    ['' => __('shopper::layout.forms.label.any')],
                    OrderStatus::values()
                ))
                ->filter(fn (Builder $query, $status) => $query->where('status', $status)),
            'created_at' => Views\Filters\DateFilter::make(__('shopper::words.date'))
                ->config([
                    'max' => now(),
                ]),
            'total' => Views\Filters\NumberFilter::make(__('shopper::words.total'))
                ->filter(fn (Builder $query, $value) => $query->where('price_amount', '>=', $value)),
            'customer' => Views\Filters\TextFilter::make(__('shopper::words.customer'))
                ->config([
                    'placeholder' => __('shopper::layout.forms.placeholder.search_by', ['label' => strtolower(__('shopper::layout.forms.label.first_name'))]),
                    'maxlength' => '25',
                ])
                ->filter(
                    fn (Builder $query, string $value) => $query->whereHas('customer', function (Builder $query) use ($value) {
                        $query->where('first_name', 'like', '%' . $value . '%')
                            ->orWhere('last_name', 'like', '%' . $value . '%');
                    })
                ),
            'product' => Views\Filters\TextFilter::make(__('shopper::words.product'))
                ->config([
                    'placeholder' => __('shopper::layout.forms.placeholder.search_by', ['label' => strtolower(__('shopper::layout.forms.label.name'))]),
                    'maxlength' => '25',
                ])
                ->filter(
                    fn (Builder $query, string $value) => $query->whereHas('items', function (Builder $query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    })
                ),
        ];
    }

    public function columns(): array
    {
        return [
            Views\Column::make('#', 'number')
                ->sortable()
                ->view('shopper::livewire.tables.cells.orders.number'),
            Views\Column::make(__('shopper::words.date'), 'created_at')
                ->sortable()
                ->format(fn ($value) => "<time datetime='" . $value->format('Y-m-d') . "' class='text-secondary-500 dark:text-secondary-400'>" . $value->diffForHumans() . '</time>')
                ->html(),
            Views\Column::make(__('shopper::layout.forms.label.status'), 'status')
                ->view('shopper::livewire.tables.cells.orders.status'),
            Views\Column::make(__('shopper::words.customer'), 'user_id')
                ->searchable(function (Builder $query, $searchTerm) {
                    $query->whereHas('customer', function (Builder $query) use ($searchTerm) {
                        $query->where('first_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                    });
                })
                ->view('shopper::livewire.tables.cells.orders.customer'),
            Views\Column::make(__('shopper::words.purchased'), 'id')
                ->view('shopper::livewire.tables.cells.orders.purchased'),
            Views\Column::make(__('shopper::words.total'), 'price_amount')
                ->format(fn ($value, $row) => "<span class='text-secondary-500 dark:text-secondary-400'>" . $row->total . '</span>')
                ->html(),
        ];
    }

    public function builder(): Builder
    {
        return Order::query()
            ->with(['customer', 'items'])
            ->withCount('items');
    }
}
