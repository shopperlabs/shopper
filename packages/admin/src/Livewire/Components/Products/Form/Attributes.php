<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Actions\Store\Product\DetachAttributesToProductAction;
use Shopper\Components\Tables\IconColumn;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\Contracts\Product;

#[Lazy]
class Attributes extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public Product $product;

    public function placeholder(): View
    {
        return view('shopper::components.skeleton.products.section');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('shopper::pages/attributes.menu'))
            ->description(__('shopper::pages/attributes.description'))
            ->query(
                AttributeProduct::with(['attribute', 'value', 'value.attribute'])
                    ->where('product_id', $this->product->id)
            )
            ->columns([
                IconColumn::make('attribute.icon')
                    ->label(__('shopper::forms.label.icon')),
                TextColumn::make('attribute.name')
                    ->label(__('shopper::forms.label.name')),
                ViewColumn::make('attribute_value_id')
                    ->label(__('shopper::forms.label.value'))
                    ->view('shopper::livewire.tables.cells.products.attribute-value'),
                TextColumn::make('attribute_custom_value')
                    ->label(__('shopper::forms.label.attribute_custom_value'))
                    ->html()
                    ->limit(150),
            ])
            ->groups([
                Group::make('attribute_id')
                    ->label(__('shopper::forms.label.attribute'))
                    ->getTitleFromRecordUsing(fn (AttributeProduct $record): string => $record->attribute->name),
            ])
            ->defaultGroup('attribute_id')
            ->headerActions([
                Action::make('choose')
                    ->label(__('shopper::pages/products.attributes.choose'))
                    ->action(
                        fn () => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.choose-product-attributes',
                            arguments: ['product' => $this->product]
                        )
                    )
                    ->visible(Attribute::query()->count() > 0),
            ])
            ->recordActions([
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (AttributeProduct $record): void {
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
            ->emptyStateIcon(Untitledui::PuzzlePiece);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.attributes');
    }
}
