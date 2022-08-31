<?php

namespace Shopper\Framework\Http\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\Shop\Order\OrderStatus;
use WireUi\Traits\Actions;

class OrdersTable extends DataTableComponent
{
    use Actions;

    public bool $columnSelect = true;

    public $columnSearch = [
        'number' => null,
    ];

    public function boot()
    {
        $this->queryString['columnSearch'] = ['except' => null];
    }

    public function bulkActions(): array
    {
        return [
            'archived' => __('Archived'),
        ];
    }

    public function archived()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            Order::whereIn('id', $this->selectedKeys())->delete();

            $this->notification()->success(__('Archived'), __('The orders has successfully archived!'));
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function filters(): array
    {
        return [
            'status' => Filter::make('Status')
                ->select(array_merge(
                    ['' => __('Any')],
                    OrderStatus::values()
                )),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'number')
                ->sortable()
                ->format(function ($value, $column, $row) {
                    return view('shopper::livewire.tables.cells.orders.number')->with('order', $row);
                }),
            Column::make('Date', 'created_at')
                ->sortable()
                ->format(function ($value) {
                    return $value ? "<time datetime='" . $value->format('Y-m-d') . "' class='capitalize text-gray-500 dark:text-gray-400'>" . $value->diffForHumans() . '</time>' : '';
                })->asHtml(),
            Column::make('Status', 'status')
                ->sortable()
                ->format(function ($value, $column, $row) {
                    return view('shopper::livewire.tables.cells.orders.status')->with('order', $row);
                }),
            Column::make('Customer', 'user_id')
                ->searchable(function (Builder $query, $searchTerm) {
                    $query->whereHas('customer', function (Builder $query) use ($searchTerm) {
                        $query->where('first_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                    });
                })
                ->sortable()
                ->format(function ($value, $column, $row) {
                    return view('shopper::livewire.tables.cells.orders.customer')->with('customer', $row->customer);
                }),
            Column::make('Purchased')
                ->format(function ($value, $column, $row) {
                    return view('shopper::livewire.tables.cells.orders.purchased')->with('order', $row);
                }),
            Column::make('Total')
                ->addClass('text-right')
                ->format(function ($value, $column, $row) {
                    return "<span class='text-gray-500 dark:text-gray-400'>" . $row->total . '</span>';
                })->asHtml(),
        ];
    }

    public function query(): Builder
    {
        return Order::query()->with(['customer', 'items'])->withCount('items')
            ->when($this->getFilter('status'), fn ($query, $status) => $query->where('status', $status));
    }
}
