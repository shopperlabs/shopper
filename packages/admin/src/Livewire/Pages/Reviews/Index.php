<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Reviews;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Review;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read array{total: int, pending: int, average: float, five_star_percent: int, this_month: int, recommended_percent: int} $stats
 * @property-read array<int, array{rating: int, count: int, percent: int}> $ratingBreakdown
 * @property-read int $recommendedPercent
 */
class Index extends AbstractPageComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    #[Url(as: 'tab', except: 'all')]
    public string $activeTab = 'all';

    public function mount(): void
    {
        $this->authorize('reviews.browse');
    }

    public function updatedActiveTab(): void
    {
        $this->resetTable();
    }

    /**
     * @return array{total: int, pending: int, average: float, five_star_percent: int, this_month: int, recommended_percent: int}
     */
    #[Computed]
    public function stats(): array
    {
        /** @var object{total: int, pending: int, average: float|string|null, five_star_count: int, recommended_count: int, this_month: int}|null $row */
        $row = DB::table(shopper_table('reviews'))
            ->selectRaw(
                'COUNT(*) as total,
                 COUNT(CASE WHEN NOT approved THEN 1 END) as pending,
                 COALESCE(AVG(rating), 0) as average,
                 COUNT(CASE WHEN rating = ? THEN 1 END) as five_star_count,
                 COUNT(CASE WHEN is_recommended THEN 1 END) as recommended_count,
                 COUNT(CASE WHEN created_at >= ? THEN 1 END) as this_month',
                [5, now()->subDays(30)]
            )
            ->first();

        $total = (int) ($row->total ?? 0);

        return [
            'total' => $total,
            'pending' => (int) ($row->pending ?? 0),
            'average' => $total > 0 ? round((float) ($row->average ?? 0), 1) : 0.0,
            'five_star_percent' => $total > 0
                ? (int) round(((int) ($row->five_star_count ?? 0) / $total) * 100)
                : 0,
            'recommended_percent' => $total > 0
                ? (int) round(((int) ($row->recommended_count ?? 0) / $total) * 100)
                : 0,
            'this_month' => (int) ($row->this_month ?? 0),
        ];
    }

    /**
     * @return array<int, array{rating: int, count: int, percent: int}>
     */
    #[Computed]
    public function ratingBreakdown(): array
    {
        $total = $this->stats['total'];
        $counts = $total > 0
            ? Review::query()
                ->selectRaw('rating, COUNT(*) as aggregate')
                ->groupBy('rating')
                ->pluck('aggregate', 'rating')
                ->all()
            : [];

        return collect([5, 4, 3, 2, 1])
            ->map(fn (int $rating): array => [
                'rating' => $rating,
                'count' => (int) ($counts[$rating] ?? 0),
                'percent' => $total > 0
                    ? (int) round((((int) ($counts[$rating] ?? 0)) / $total) * 100)
                    : 0,
            ])
            ->all();
    }

    #[Computed]
    public function recommendedPercent(): int
    {
        return $this->stats['recommended_percent'];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Review::query()
                    ->with(['author', 'reviewrateable'])
                    ->when(
                        $this->activeTab === 'pending',
                        fn (Builder $query): Builder => $query->where('approved', false)
                    )
                    ->when(
                        $this->activeTab === 'approved',
                        fn (Builder $query): Builder => $query->where('approved', true)
                    )
            )
            ->columns([
                TextColumn::make('author.full_name')
                    ->label(__('shopper::words.customer'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (Review $record): View => view(
                        'shopper::components.user-avatar',
                        ['user' => $record->author]
                    )),
                TextColumn::make('reviewrateable.name')
                    ->label(__('shopper::words.product'))
                    ->searchable()
                    ->sortable()
                    ->url(fn (Review $record): string => route(
                        name: 'shopper.products.edit',
                        parameters: ['product' => $record->reviewrateable]
                    )),
                ViewColumn::make('rating')
                    ->label(__('shopper::pages/products.reviews.rating'))
                    ->view('shopper::livewire.tables.cells.reviews.rating'),
                TextColumn::make('content')
                    ->label(__('shopper::pages/products.reviews.review'))
                    ->limit(30)
                    ->tooltip(fn (Review $record): string => $record->content)
                    ->toggledHiddenByDefault()
                    ->toggleable(),
                TextColumn::make('approved')
                    ->label(__('shopper::forms.label.status'))
                    ->badge()
                    ->formatStateUsing(
                        fn (bool $state): string => $state
                            ? __('shopper::pages/products.reviews.published')
                            : __('shopper::pages/products.reviews.pending')
                    )
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
                TextColumn::make('created_at')
                    ->label(__('shopper::words.date'))
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label(__('shopper::forms.actions.view'))
                    ->icon(Untitledui::Eye)
                    ->iconButton()
                    ->action(
                        fn (Review $record) => $this->dispatch(
                            'openPanel',
                            'shopper-slide-overs.review-detail',
                            ['review' => $record]
                        )
                    ),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Review $record) => $record->delete())
                    ->authorize('reviews.delete')
                    ->visible(shopper()->auth()->user()->can('reviews.delete')),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(__('shopper::notifications.delete', ['item' => __('shopper::pages/products.reviews.single')]))
                            ->success()
                            ->send();
                    })
                    ->authorize('reviews.delete')
                    ->visible(shopper()->auth()->user()->can('reviews.delete'))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->filters([
                SelectFilter::make('rating')
                    ->label(__('shopper::pages/products.reviews.rating'))
                    ->options([
                        1 => '1 '.__('shopper::pages/products.reviews.star'),
                        2 => '2 '.__('shopper::pages/products.reviews.stars'),
                        3 => '3 '.__('shopper::pages/products.reviews.stars'),
                        4 => '4 '.__('shopper::pages/products.reviews.stars'),
                        5 => '5 '.__('shopper::pages/products.reviews.stars'),
                    ]),
                SelectFilter::make('reviewrateable_id')
                    ->label(__('shopper::words.product'))
                    ->options(
                        fn (): array => resolve(Product::class)::query()
                            ->whereHas('ratings')
                            ->pluck('name', 'id')
                            ->all()
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('author_id')
                    ->label(__('shopper::words.customer'))
                    ->options(
                        fn (): array => Review::query()
                            ->with('author')
                            ->get()
                            ->pluck('author.full_name', 'author_id')
                            ->unique()
                            ->all()
                    )
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_recommended')
                    ->label(__('shopper::pages/products.reviews.is_recommended')),
            ])
            ->emptyState(view('shopper::livewire.tables.empty-states.reviews'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.reviews.index')
            ->title(__('shopper::pages/reviews.title'));
    }
}
