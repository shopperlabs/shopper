<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Shopper\Actions\Store\Product\DetachAttributesToProductAction;
use Shopper\Components\Tables\IconColumn;
use Shopper\Core\Models\AttributeProduct;

#[Lazy]
class Attributes extends Component implements HasForms, HasTable
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
                AttributeProduct::with(['attribute', 'value', 'value.attribute'])
                    ->where('product_id', $this->product->id)
            )
            ->columns([
                IconColumn::make('attribute.icon')
                    ->label(__('shopper::forms.label.icon')),
                Tables\Columns\TextColumn::make('attribute.name')
                    ->label(__('shopper::forms.label.name')),
                Tables\Columns\ViewColumn::make('attribute_value_id')
                    ->label(__('shopper::forms.label.value'))
                    ->view('shopper::livewire.tables.cells.products.attribute-value'),
                Tables\Columns\TextColumn::make('attribute_custom_value')
                    ->label(__('shopper::forms.label.attribute_custom_value'))
                    ->html()
                    ->limit(150),
            ])
            ->groups([
                Tables\Grouping\Group::make('attribute_id')
                    ->label(__('shopper::forms.label.attribute'))
                    ->getTitleFromRecordUsing(fn ($record): string => $record->attribute->name),
            ])
            ->defaultGroup('attribute_id')
            ->headerActions([
                Tables\Actions\Action::make('choose')
                    ->label(__('shopper::pages/products.attributes.choose'))
                    ->action(
                        fn () => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.choose-product-attributes',
                            arguments: ['productId' => $this->product->id]
                        )
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('delete')
                    ->icon('untitledui-trash-03')
                    ->label(__('shopper::forms.actions.delete'))
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $this->authorize('delete_attributes', $record);

                        app()->call(DetachAttributesToProductAction::class, [
                            'attributeProduct' => $record,
                            'product' => $this->product,
                        ]);

                        $this->dispatch('product.updated');
                    })
                    ->successNotificationTitle(__('shopper::pages/products.attributes.session.delete_message')),
            ])
            ->emptyStateHeading(__('shopper::pages/products.attributes.empty_title'))
            ->emptyStateDescription(__('shopper::pages/products.attributes.empty_values'))
            ->emptyStateIcon('untitledui-puzzle-piece');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.attributes');
    }
}
