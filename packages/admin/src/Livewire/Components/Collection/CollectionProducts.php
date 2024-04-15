<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Collection;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Models\Collection;

class CollectionProducts extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Collection $collection;

    #[Computed]
    public function productsIds(): array
    {
        return $this->collection->products->modelKeys();
    }

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn (): BelongsToMany => $this->collection->products())
            ->inverseRelationship('collections')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label(__('shopper::layout.forms.label.thumbnail'))
                    ->collection(config('shopper.core.storage.thumbnail_collection'))
                    ->circular()
                    ->defaultImageUrl(config('shopper.media.fallback_url')),
                Tables\Columns\TextColumn::make('name'),
            ])
            ->actions([
                Tables\Actions\Action::make(__('shopper::layout.forms.actions.delete'))
                    ->icon('untitledui-trash-03')
                    ->iconButton()
                    ->modalIcon('untitledui-trash-03')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $this->collection->products()->detach([$record->id]);

                        $this->dispatch('onProductsAddInCollection');

                        Notification::make()
                            ->title(__('shopper::pages/collections.remove_product'))
                            ->success()
                            ->send();
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('rules')
                    ->label(__('shopper::pages/collections.conditions.title'))
                    ->icon('untitledui-ruler')
                    ->button()
                    ->color('gray')
                    ->action(fn () => $this->dispatch(
                        'openPanel',
                        component: 'shopper-slide-overs.collection-rules',
                        arguments: ['collection' => $this->collection]
                    ))
                    ->visible($this->collection->isAutomatic()),

                Tables\Actions\Action::make('products')
                    ->label(__('shopper::layout.forms.label.browse'))
                    ->icon('untitledui-book-open')
                    ->button()
                    ->color('gray')
                    ->action(fn () => $this->dispatch(
                        'openModal',
                        component: 'shopper-modals.products-list',
                        arguments: [
                            'collectionId' => $this->collection->id,
                            'exceptProductIds' => $this->productsIds,
                        ]
                    ))
                    ->visible($this->collection->isManual()),
            ])
            ->emptyStateIcon('untitledui-book-open')
            ->emptyStateDescription(__('shopper::pages/collections.empty_collections'));
    }

    #[On('onProductsAddInCollection')]
    public function render(): View
    {
        return view('shopper::livewire.components.collections.products');
    }
}
