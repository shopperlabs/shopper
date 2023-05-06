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
    public $columnSearch = [
        'name' => null,
        'author' => null,
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects([
                'id',
                'title',
                'rating',
                'content',
                'is_recommended',
                'reviewrateable_type',
                'author_type',
            ])
            ->setTdAttributes(function (Views\Column $column) {
                if ($column->isField('reviewrateable_id')) {
                    return [
                        'class' => 'w-full max-w-xl whitespace-nowrap',
                    ];
                }

                if ($column->isField('content')) {
                    return [
                        'class' => 'hidden md:table-cell whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400',
                    ];
                }

                if ($column->isField('author_id')) {
                    return [
                        'class' => 'table-cell whitespace-no-wrap text-sm leading-5',
                    ];
                }

                return [];
            })
            ->setBulkActions([
                'deleteSelected' => __('shopper::layout.forms.actions.delete'),
                'approved' => __('shopper::layout.forms.actions.approve'),
                'disapproved' => __('shopper::layout.forms.actions.disapprove'),
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
                ->body(__('shopper::components.tables.messages.delete', ['name' => __('shopper::words.review')]))
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
                ->body(__('shopper::components.tables.messages.approved', ['name' => __('shopper::words.review')]))
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
                ->body(__('shopper::components.tables.messages.disapproved', ['name' => __('shopper::words.review')]))
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
            Views\Column::make(__('shopper::words.product'), 'reviewrateable_id')
                ->sortable()
                ->secondaryHeader(function () {
                    return view('shopper::livewire.tables.cells.input-search', [
                        'field' => 'name',
                        'columnSearch' => $this->search,
                    ]);
                })
                ->view('shopper::livewire.tables.cells.reviews.product'),
            Views\Column::make(__('shopper::pages/products.reviews.reviewer'), 'author_id')
                ->view('shopper::livewire.tables.cells.reviews.author'),
            Views\Column::make(__('shopper::pages/products.reviews.review'), 'content')
                ->view('shopper::livewire.tables.cells.reviews.content'),
            Views\Columns\BooleanColumn::make(__('shopper::pages/products.reviews.status'), 'approved'),
        ];
    }

    public function builder(): Builder
    {
        return Review::query()
            ->with(['reviewrateable', 'reviewrateable.media', 'author'])
            ->whereHasMorph('reviewrateable', config('shopper.system.models.product'), function (Builder $query) {
                $query->when(
                    $this->columnSearch['name'] ?? null,
                    fn (Builder $query, $name) => $query->where('name', 'like', '%' . $name . '%')
                );
            });
    }
}
