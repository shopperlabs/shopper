<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Collection;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Collection;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read array<int> $productsIds
 */
class CollectionProducts extends Component implements HasActions, HasForms, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public Collection $collection;

    /**
     * @return array<int>
     */
    #[Computed]
    public function productsIds(): array
    {
        return $this->collection->products->pluck('id')->toArray();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('shopper::pages/products.menu'))
            ->description($this->collection->isAutomatic() ? __('shopper::pages/collections.automatic_description') : __('shopper::pages/collections.manual_description'))
            ->relationship(fn (): BelongsToMany => $this->collection->products())
            ->inverseRelationship('collections')
            ->columns([
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->circular()
                    ->defaultImageUrl(shopper_fallback_url()),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->badge(),
                TextColumn::make('sku')
                    ->label(__('shopper::forms.label.sku')),
            ])
            ->recordActions([
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Product $record): void {
                        $this->collection->products()->detach([$record->id]);

                        $this->dispatch('collection.add.product');

                        Notification::make()
                            ->title(__('shopper::pages/collections.remove_product'))
                            ->success()
                            ->send();
                    }),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->requiresConfirmation()
                    ->action(function (EloquentCollection $records): void {
                        $this->collection->products()->detach($records->pluck('id')->toArray());

                        $this->dispatch('collection.add.product');

                        Notification::make()
                            ->title(__('shopper::pages/collections.remove_product'))
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ])
            ->headerActions([
                Action::make('rules')
                    ->label(__('shopper::pages/collections.conditions.title'))
                    ->icon(Untitledui::Ruler)
                    ->button()
                    ->color('gray')
                    ->action(fn () => $this->dispatch(
                        'openPanel',
                        component: 'shopper-slide-overs.collection-rules',
                        arguments: ['collection' => $this->collection]
                    ))
                    ->visible($this->collection->isAutomatic()),
                Action::make('products')
                    ->label(__('shopper::forms.label.browse'))
                    ->icon(Untitledui::BookOpen)
                    ->button()
                    ->color('gray')
                    ->action(fn () => $this->dispatch(
                        'openPanel',
                        component: 'shopper-slide-overs.collection-products-list',
                        arguments: [
                            'collection' => $this->collection,
                            'exceptProductIds' => $this->productsIds,
                        ]
                    ))
                    ->visible($this->collection->isManual()),
            ])
            ->emptyStateIcon(Untitledui::BookOpen)
            ->emptyStateDescription(__('shopper::pages/collections.empty_collections'));
    }

    #[On('collection.add.product')]
    public function render(): View
    {
        return view('shopper::livewire.components.collections.products');
    }
}
