<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Events\Products\Deleted as ProductDeleted;
use Shopper\Core\Models\Product;
use Shopper\Core\Repositories\Store\ProductRepository;

class Variants extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $product;

    public function mount($product): void
    {
        $this->product = $product;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                (new ProductRepository())
                    ->makeModel()
                    ->where('parent_id', $this->product->id)
                    ->newQuery()
            )
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('images')
                    ->collection(config('shopper.core.storage.collection_name'))
                    ->stacked()
                    ->circular()
                    ->wrap()
                    ->defaultImageUrl(config('shopper.media.fallback_url')),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('shopper::layout.forms.label.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label(__('shopper::layout.tables.sku'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label(__('shopper::layout.tables.current_stock'))
                    ->formatStateUsing(
                        fn (Product $record): HtmlString => new HtmlString(Blade::render(<<<BLADE
                            <div class="flex items-center">
                                <x-shopper::stock-badge :stock="{$record->stock}" />
                                {{ __('shopper::words.in_stock') }}
                            </div>
                        BLADE))
                    ),

                Tables\Columns\TextColumn::make('price_amount')
                    ->label(__('shopper::layout.forms.label.price'))
                    ->money(shopper_currency())
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('edit')
                        ->label(__('shopper::layout.forms.actions.edit'))
                        ->icon('untitledui-edit-04')
                        ->url(
                            fn (Product $record): string => route(
                                name: 'shopper.products.variant',
                                parameters: ['variantId' => $record->id, 'product' => $this->product]
                            ),
                        ),
                    Tables\Actions\Action::make(__('shopper::layout.forms.actions.delete'))
                        ->icon('untitledui-trash-03')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalIcon('untitledui-trash-03')
                        ->action(function (Product $record): void {
                            event(new ProductDeleted($record));

                            $record->forceDelete();

                            $this->dispatch('onVariantsUpdated');

                            Notification::make()
                                ->title(__('shopper::pages/products.notifications.variation_delete'))
                                ->success()
                                ->send();
                        }),
                ])
                    ->tooltip('Actions'),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('shopper::layout.forms.actions.delete'))
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(__('shopper::components.tables.status.delete'))
                            ->body(
                                __('shopper::components.tables.messages.delete', [
                                    'name' => mb_strtolower(__('shopper::words.variant')),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('add')
                    ->label(__('shopper::pages/products.variants.add'))
                    ->action(
                        fn () => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.add-variant',
                            arguments: ['productId' => $this->product->id]
                        )
                    ),
            ])
            ->emptyStateHeading(__('shopper::pages/products.variants.empty'))
            ->emptyStateIcon('untitledui-book-open');
    }

    #[On('onVariantsUpdated')]
    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.variants');
    }
}
