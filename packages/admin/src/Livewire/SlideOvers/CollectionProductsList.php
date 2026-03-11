<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Attributes\Locked;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Collection;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class CollectionProductsList extends SlideOverComponent implements HasActions, HasForms, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public Collection $collection;

    /** @var array<int> */
    #[Locked]
    public array $exceptProductIds = [];

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    /**
     * @param  array<int>  $exceptProductIds
     */
    public function mount(?Collection $collection = null, array $exceptProductIds = []): void
    {
        $this->authorize('edit_collections');

        $this->collection = $collection;
        $this->exceptProductIds = $exceptProductIds;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                resolve(Product::class)::query()
                    ->scopes('publish')
                    ->when(
                        $this->exceptProductIds,
                        fn (Builder $query) => $query->whereNotIn('id', $this->exceptProductIds)
                    )
            )
            ->columns([
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->circular()
                    ->defaultImageUrl(shopper_fallback_url()),
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->badge(),
                TextColumn::make('sku')
                    ->label(__('shopper::forms.label.sku')),
                ViewColumn::make('stock')
                    ->label(__('shopper::layout.tables.stock'))
                    ->view('shopper::livewire.tables.cells.products.stock')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->searchable()
            ->selectable()
            ->toolbarActions([
                BulkAction::make('add')
                    ->label(__('shopper::pages/collections.modal.action'))
                    ->icon(Untitledui::Plus)
                    ->action(function (EloquentCollection $records): void {
                        /** @var array<int> $currentProducts */
                        $currentProducts = $this->collection->products->pluck('id')->toArray();

                        $this->collection->products()->sync(
                            array_merge($records->pluck('id')->toArray(), $currentProducts)
                        );

                        $this->dispatch('collection.add.product');

                        Notification::make()
                            ->title(__('shopper::pages/collections.modal.success_message'))
                            ->success()
                            ->send();

                        $this->closePanel();
                    }),
            ])
            ->emptyStateIcon(Untitledui::BookOpen)
            ->emptyStateHeading(__('shopper::pages/products.related.modal.no_results'));
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.collection-products-list');
    }
}
