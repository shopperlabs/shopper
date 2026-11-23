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
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Tables\RatingColumn;
use Shopper\Components\Tables\UserColumn;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Review;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Models\Contracts\ShopperUser;
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
        $row = Review::query()
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
                UserColumn::make('author.full_name')
                    ->label(__('shopper::words.customer'))
                    ->user(fn (Review $record) => $record->author)
                    ->searchable(
                        query: fn (Builder $query, string $search): Builder => $query->orWhereHas(
                            'author',
                            fn (Builder $query): Builder => $query
                                ->whereLike('first_name', "%{$search}%")
                                ->orWhereLike('last_name', "%{$search}%"),
                        ),
                    )
                    ->sortable(
                        query: fn (Builder $query, string $direction): Builder => $query->orderBy(
                            $this->authorNameSubQuery(),
                            $direction,
                        ),
                    ),
                TextColumn::make('reviewrateable.name')
                    ->label(__('shopper::words.product'))
                    ->searchable(
                        query: fn (Builder $query, string $search): Builder => $query
                            ->where('reviewrateable_type', resolve(Product::class)->getMorphClass())
                            ->whereIn(
                                'reviewrateable_id',
                                resolve(Product::class)::query()
                                    ->select('id')
                                    ->whereLike('name', "%{$search}%"),
                            ),
                    )
                    ->sortable(
                        query: fn (Builder $query, string $direction): Builder => $query->orderBy(
                            $this->productNameSubQuery(),
                            $direction,
                        ),
                    )
                    ->url(fn (Review $record): ?string => $record->reviewrateable
                        ? route(name: 'shopper.products.edit', parameters: ['product' => $record->reviewrateable])
                        : null),
                RatingColumn::make('rating')
                    ->label(__('shopper::pages/products.reviews.rating')),
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
                    ->multiple()
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => resolve(Product::class)::query()
                        ->whereHas('ratings')
                        ->whereLike('name', "%{$search}%")
                        ->limit(50)
                        ->pluck('name', 'id')
                        ->all())
                    ->getOptionLabelsUsing(fn (array $values): array => $this->productNames($values))
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['values'] ?? null,
                        fn (Builder $query, array $ids): Builder => $query
                            ->where('reviewrateable_type', resolve(Product::class)->getMorphClass())
                            ->whereIn('reviewrateable_id', $ids),
                    ))
                    ->indicateUsing(function (array $state): ?string {
                        $names = $this->productNames($state['values'] ?? []);

                        return $names === [] ? null : __('shopper::words.product').': '.collect($names)->join(', ', ' & ');
                    }),
                SelectFilter::make('author_id')
                    ->label(__('shopper::words.customer'))
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => $this->reviewAuthors()
                        ->where(fn (Builder $query): Builder => $query
                            ->whereLike('first_name', "%{$search}%")
                            ->orWhereLike('last_name', "%{$search}%"))
                        ->limit(50)
                        ->get()
                        ->pluck('full_name', $this->userModel()->getKeyName())
                        ->all())
                    ->getOptionLabelUsing(fn (int|string $value): ?string => $this->reviewAuthorName($value))
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, int|string $id): Builder => $query
                            ->where('author_type', $this->userModel()->getMorphClass())
                            ->where('author_id', $id),
                    ))
                    ->indicateUsing(function (array $state): ?string {
                        $name = filled($state['value'] ?? null) ? $this->reviewAuthorName($state['value']) : null;

                        return $name === null ? null : __('shopper::words.customer').': '.$name;
                    }),
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

    /**
     * @return Builder<Model>
     */
    private function authorNameSubQuery(): Builder
    {
        $user = $this->userModel();

        return $user::query()
            ->select('first_name')
            ->whereColumn($user->getTable().'.id', shopper_table('reviews').'.author_id');
    }

    /**
     * @param  array<int|string>  $ids
     * @return array<int|string, string>
     */
    private function productNames(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        return resolve(Product::class)::query()->whereKey($ids)->pluck('name', 'id')->all();
    }

    /**
     * @return Builder<Model>
     */
    private function reviewAuthors(): Builder
    {
        $user = $this->userModel();

        return $user::query()->whereIn(
            $user->getKeyName(),
            Review::query()->select('author_id')->where('author_type', $user->getMorphClass()),
        );
    }

    private function userModel(): Model
    {
        /** @var Model */
        return resolve(config('auth.providers.users.model'));
    }

    private function reviewAuthorName(int|string $id): ?string
    {
        /** @var (Model&ShopperUser)|null $author */
        $author = $this->reviewAuthors()->find($id);

        return $author?->full_name;
    }

    /**
     * @return Builder<Model>
     */
    private function productNameSubQuery(): Builder
    {
        $product = resolve(Product::class);

        return $product::query()
            ->select('name')
            ->whereColumn($product->getTable().'.id', shopper_table('reviews').'.reviewrateable_id');
    }
}
