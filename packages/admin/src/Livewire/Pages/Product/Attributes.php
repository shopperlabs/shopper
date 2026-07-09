<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Actions\Store\Product\DetachAttributesToProductAction;
use Shopper\Components\Tables\IconColumn;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\Contracts\AttributeProduct as AttributeProductContract;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Lazy]
#[Layout('shopper::components.layouts.product')]
class Attributes extends Component implements HasActions, HasSchemas, HasTable
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
            ->heading(__('shopper::pages/attributes.menu'))
            ->description(__('shopper::pages/attributes.description'))
            ->query(
                resolve(AttributeProductContract::class)
                    ->newQuery()
                    ->with(['attribute', 'value', 'value.attribute', 'media'])
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
                            'shopper-slide-overs.choose-product-attributes',
                            ['product' => $this->product]
                        )
                    )
                    ->visible(Attribute::query()->count() > 0),
            ])
            ->recordActions([
                Action::make('swatch')
                    ->authorize('products.edit')
                    ->label(__('shopper::pages/products.attributes.swatch.action'))
                    ->icon(Untitledui::Image)
                    ->iconButton()
                    ->color('gray')
                    ->modalWidth(Width::Large)
                    ->fillForm(fn (AttributeProduct $record): array => $record->toArray())
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('swatch')
                            ->collection('swatch')
                            ->label(__('shopper::pages/products.attributes.swatch.label'))
                            ->helperText(__('shopper::pages/products.attributes.swatch.help_text'))
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                            ->maxSize(config('shopper.media.max_size.thumbnail')),
                    ])
                    ->successNotificationTitle(__('shopper::pages/products.attributes.swatch.updated')),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (AttributeProduct $record): void {
                        $this->authorize('attributes.delete', $record);

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
        return view('shopper::livewire.components.products.forms.attributes')
            ->title($this->product->name.' ~ '.__('shopper::pages/attributes.menu'));
    }
}
