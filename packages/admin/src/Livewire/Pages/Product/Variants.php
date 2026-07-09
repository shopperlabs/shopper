<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Lazy]
#[Layout('shopper::components.layouts.product')]
class Variants extends Component implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    #[Locked]
    public Product $product;

    public function placeholder(): View
    {
        return view('shopper::components.skeleton.products.section');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('shopper::pages/products.variants.title'))
            ->description(__('shopper::pages/products.variants.description'))
            ->query(
                resolve(ProductVariant::class)::query()
                    ->where('product_id', $this->product->id)
                    ->with('media')
            )
            ->defaultSort('position')
            ->reorderable('position')
            ->authorizeReorder(shopper()->auth()->user()->can('products.variants.edit'))
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->getStateUsing(fn (ProductVariant $record): string => $record->getThumbnailUrl())
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sku')
                    ->label(__('shopper::layout.tables.sku'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position')
                    ->label(__('shopper::forms.label.position'))
                    ->sortable(),
                TextColumn::make('stock')
                    ->label(__('shopper::layout.tables.current_stock'))
                    ->formatStateUsing(
                        fn (ProductVariant $record): HtmlString => new HtmlString(Blade::render(<<<BLADE
                            <div class="flex items-center">
                                <x-shopper::stock-badge :stock="{$record->stock}" />
                                {{ __('shopper::words.in_stock') }}
                            </div>
                        BLADE))
                    ),
            ])
            ->headerActions([
                Action::make('generate')
                    ->icon($this->product->type->getIcon())
                    ->label(__('shopper::pages/products.variants.generate'))
                    ->color('gray')
                    ->action(
                        fn () => $this->dispatch(
                            'openPanel',
                            'shopper-slide-overs.generate-variants',
                            ['product' => $this->product]
                        )
                    )
                    ->visible($this->product->options->count() > 0),
                Action::make('add')
                    ->label(__('shopper::pages/products.variants.add'))
                    ->action(
                        fn () => $this->dispatch(
                            'openPanel',
                            'shopper-slide-overs.add-variant',
                            ['product' => $this->product]
                        )
                    ),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->iconButton()
                    ->icon(Untitledui::Edit03)
                    ->action(
                        fn (ProductVariant $record) => $this->redirectRoute(
                            name: 'shopper.products.variant',
                            parameters: ['product' => $this->product, 'variant' => $record],
                            navigate: true
                        ),
                    ),
                DeleteAction::make()
                    ->authorize('products.variants.delete')
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->modalIcon(Untitledui::Trash03)
                    ->successNotificationTitle(__('shopper::pages/products.notifications.variation_delete')),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->authorize('products.variants.delete')
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
            ->emptyStateIcon(Untitledui::BookOpen);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.variants')
            ->title($this->product->name.' ~ '.__('shopper::words.variants'));
    }
}
