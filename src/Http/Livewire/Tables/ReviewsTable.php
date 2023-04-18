<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Tables;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views;
use Shopper\Framework\Models\Shop\Review;

class ReviewsTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('key')
            ->setAdditionalSelects(['id', 'rating', 'content'])
            ->setBulkActions([
                'deleteSelected' => __('Delete'),
                'approved' => __('Approved'),
                'disapproved' => __('Disapproved'),
            ]);
    }

    public function boot(): void
    {
        $this->queryString['columnSearch'] = ['except' => null];
    }

    public function deleteSelected(): void
    {
        if (count($this->getSelected()) > 0) {
            Review::query()
                ->whereIn('id', $this->getSelected())
                ->delete();

            Notification::make()
                ->title(__('shopper::components.tables.status.delete'))
                ->body(__('shopper::components.tables.messages.delete', ['name' => __('review(s)')]))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    public function approved(): void
    {
        if (count($this->getSelected()) > 0) {
            Review::query()
                ->whereIn('id', $this->getSelected())
                ->update(['approved' => true]);

            Notification::make()
                ->title(__('shopper::components.tables.status.updated'))
                ->body(__('shopper::components.tables.messages.approved', ['name' => __('review(s)')]))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    public function disapproved(): void
    {
        if (count($this->getSelected()) > 0) {
            Review::query()
                ->whereIn('id', $this->getSelected())
                ->update(['approved' => false]);

            Notification::make()
                ->title(__('shopper::components.tables.status.updated'))
                ->body(__('shopper::components.tables.messages.disapproved', ['name' => __('review(s)')]))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    public function filters(): array
    {
        return [
            'approved' => Views\Filters\SelectFilter::make(__('shopper::pages/products.reviews.approved'))
                ->options([
                    '' => __('shopper::layout.forms.label.any'),
                    'yes' => __('shopper::layout.forms.label.yes'),
                    'no' => __('shopper::layout.forms.label.no'),
                ])
                ->filter(fn (Builder $query, $value) => $query->where('approved', $value === 'yes')),
        ];
    }

    public function columns(): array
    {
        return [
            Views\Column::make(__('shopper::messages.product'), 'name')
                ->sortable()
                ->secondaryHeader(function () {
                    return view('shopper::livewire.tables.cells.input-search', ['field' => 'name', 'columnSearch' => $this->columnSearch]);
                }),
            Views\Column::make(__('shopper::pages/products.reviews.reviewer'))->sortable(),
            Views\Column::make(__('shopper::pages/products.reviews.review'))->sortable(),
            Views\Column::make(__('shopper::pages/products.reviews.status'))->sortable(),
        ];
    }

    public function query(): Builder
    {
        return Review::query()
            ->with(['reviewrateable', 'author'])
            ->whereHasMorph('reviewrateable', config('shopper.system.models.product'), function (Builder $query) {
                $query->when($this->columnSearch['name'] ?? null, fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'));
            });
    }
}
