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
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Shopper\Core\Repositories\VariantRepository;

#[Lazy]
class Variants extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $product;

    public function placeholder(): View
    {
        return view('shopper::components.skeleton.products.section');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                (new VariantRepository)
                    ->query()
                    ->where('product_id', $this->product->id)
                    ->latest()
            )
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->square(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label(__('shopper::layout.tables.sku'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label(__('shopper::forms.label.position'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label(__('shopper::layout.tables.current_stock'))
                    ->formatStateUsing(
                        fn ($record): HtmlString => new HtmlString(Blade::render(<<<BLADE
                            <div class="flex items-center">
                                <x-shopper::stock-badge :stock="{$record->stock}" />
                                {{ __('shopper::words.in_stock') }}
                            </div>
                        BLADE))
                    ),
            ])
            ->headerActions([
                Tables\Actions\Action::make('generate')
                    ->icon($this->product->type->getIcon())
                    ->label(__('shopper::pages/products.variants.generate'))
                    ->color('gray')
                    ->action(
                        fn () => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.generate-variants',
                            arguments: ['productId' => $this->product->id]
                        )
                    )
                    ->visible($this->product->options->count() > 0),
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
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon('untitledui-edit-04')
                    ->action(
                        fn ($record) => $this->redirectRoute(
                            name: 'shopper.products.variant',
                            parameters: ['productId' => $this->product->id, 'variantId' => $record->id],
                        ),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->icon('untitledui-trash-03')
                    ->modalIcon('untitledui-trash-03')
                    ->successNotificationTitle(__('shopper::pages/products.notifications.variation_delete')),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(__('shopper::forms.actions.delete'))
                            ->body(
                                __('shopper::notifications.delete', [
                                    'item' => __('shopper::pages/products.variants.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ])
            ->emptyStateHeading(__('shopper::pages/products.variants.empty'))
            ->emptyStateIcon('untitledui-book-open');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.variants');
    }
}
