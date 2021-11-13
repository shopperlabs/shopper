<?php

namespace Shopper\Framework\Http\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Shopper\Framework\Models\Shop\Review;
use WireUi\Traits\Actions;

class ReviewsTable extends DataTableComponent
{
    use Actions;

    public array $bulkActions = [
        'deleteSelected' => 'Delete',
        'approved' => 'Approved',
        'disapproved' => 'Disapproved',
    ];

    public $columnSearch = [
        'name' => null,
        'email' => null,
    ];

    public array $filterNames = [
        'approved' => 'Approved',
    ];

    public function boot()
    {
        $this->queryString['columnSearch'] = ['except' => null];
    }

    public function deleteSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            Review::whereIn('id', $this->selectedKeys())->delete();

            $this->notification()->success(__('Deleted'), __('The review(s) has successfully deleted!'));
        }

        $this->selected = [];

        $this->resetAll();
    }

    public function approved()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            Review::whereIn('id', $this->selectedKeys())->update(['approved' => true]);

            $this->notification()->success(__('Updated'), __('The reviews has successfully approved!'));
        }

        $this->selected = [];

        $this->resetBulk();
    }

    public function disapproved()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            Review::whereIn('id', $this->selectedKeys())->update(['approved' => false]);

            $this->notification()->success(__('Updated'), __('The reviews has successfully disapproved!'));
        }

        $this->selected = [];

        $this->resetBulk();
    }

    public function filters(): array
    {
        return [
            'approved' => Filter::make('Approved Review')
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
            Column::make('Product', 'name')
                ->sortable()
                ->asHtml()
                ->secondaryHeader(function () {
                    return view('shopper::livewire.tables.cells.input-search', ['field' => 'name', 'columnSearch' => $this->columnSearch]);
                }),
            Column::make('Reviewer')->sortable(),
            Column::make('Review')->sortable(),
            Column::make('Status')->sortable(),
        ];
    }

    public function query(): Builder
    {
        return Review::query()
            ->with(['reviewrateable', 'author'])
            ->whereHasMorph('reviewrateable', config('shopper.system.models.product'), function (Builder $query) {
                $query->when($this->columnSearch['name'] ?? null, fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'));
            })
            ->when($this->getFilter('approved'), fn ($query, $active) => $query->where('approved', $active === 'yes'));
    }

    public function rowView(): string
    {
        return 'shopper::livewire.tables.rows.reviews-table';
    }
}
