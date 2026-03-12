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
use Illuminate\Database\Eloquent\Collection;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Review;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Index extends AbstractPageComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_reviews');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Review::query()->with(['author', 'reviewrateable'])
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
                    ->sortable(),
                ViewColumn::make('rating')
                    ->label(__('shopper::pages/products.reviews.rating'))
                    ->view('shopper::livewire.tables.cells.reviews.rating'),
                TextColumn::make('content')
                    ->label(__('shopper::pages/products.reviews.review'))
                    ->limit(50)
                    ->tooltip(fn (Review $record): string => $record->content)
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
                            component: 'shopper-slide-overs.review-detail',
                            arguments: ['review' => $record]
                        )
                    ),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Review $record) => $record->delete())
                    ->authorize('delete_reviews')
                    ->visible(shopper()->auth()->user()->can('delete_reviews')),
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
                    ->authorize('delete_reviews')
                    ->visible(shopper()->auth()->user()->can('delete_reviews'))
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
                TernaryFilter::make('approved')
                    ->label(__('shopper::pages/products.reviews.approved_status')),
                TernaryFilter::make('is_recommended')
                    ->label(__('shopper::pages/products.reviews.is_recommended')),
            ])
            ->emptyState(view('shopper::livewire.tables.empty-states.reviews'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.reviews.index')
            ->title(__('shopper::pages/reviews.menu'));
    }
}
