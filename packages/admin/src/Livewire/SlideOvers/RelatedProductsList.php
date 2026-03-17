<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Livewire\Attributes\Locked;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Traits\HandlesAuthorizationExceptions;

class RelatedProductsList extends SlideOverComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public Product $product;

    /** @var array<int> */
    #[Locked]
    public array $exceptProductIds = [];

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    /**
     * @param  array<int>  $ids
     */
    public function mount(?Product $product = null, array $ids = []): void
    {
        $this->authorize('edit_products');

        $this->product = $product;
        $this->exceptProductIds = $ids;
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
                    ->action(function (Collection $records): void {
                        /** @var array<int> $currentProducts */
                        $currentProducts = $this->product->relatedProducts->pluck('id')->toArray();

                        $this->product->relatedProducts()->sync(
                            array_merge($records->pluck('id')->toArray(), $currentProducts)
                        );

                        Notification::make()
                            ->title(__('shopper::layout.status.added'))
                            ->body(__('shopper::pages/products.notifications.related_added'))
                            ->success()
                            ->send();

                        $this->redirect(
                            route('shopper.products.edit', ['product' => $this->product, 'tab' => 'related']),
                            navigate: true
                        );
                    }),
            ])
            ->emptyStateIcon(Untitledui::BookOpen)
            ->emptyStateHeading(__('shopper::pages/products.related.modal.no_results'));
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.related-products-list');
    }
}
