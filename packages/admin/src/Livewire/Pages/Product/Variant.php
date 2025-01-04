<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Shopper\Components;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Core\Repositories\VariantRepository;
use Shopper\Livewire\Components\Products\Pricing;
use Shopper\Livewire\Components\Products\VariantStock;
use Shopper\Livewire\Pages\AbstractPageComponent;

/**
 * @property Form $form
 */
class Variant extends AbstractPageComponent implements HasForms
{
    use InteractsWithForms;

    public $product;

    public $variant;

    public ?array $data = [];

    public function mount(int $productId, int $variantId): void
    {
        $this->authorize('edit_products');

        $this->product = (new ProductRepository)->getById($productId);
        $this->variant = (new VariantRepository)->with('prices')->getById($variantId);

        $this->form->fill($this->variant->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Section::make(__('shopper::pages/products.variants.variant_information'))
                    ->compact()
                    ->aside()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('shopper::forms.label.name'))
                                    ->placeholder('Model Y, Model S (Eg. for Tesla)')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Checkbox::make('allow_backorder')
                                    ->label(__('shopper::pages/products.product_can_returned'))
                                    ->helperText(__('shopper::pages/products.product_can_returned_help_text')),
                            ]),
                    ]),

                Components\Separator::make(),

                Components\Section::make(__('shopper::words.media'))
                    ->compact()
                    ->aside()
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                            ->collection(config('shopper.media.storage.thumbnail_collection'))
                            ->label(__('shopper::forms.label.thumbnail'))
                            ->helperText(__('shopper::pages/products.thumbnail_helpText'))
                            ->image()
                            ->maxSize(config('shopper.media.max_size.thumbnail')),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                            ->multiple()
                            ->label(__('shopper::words.images'))
                            ->panelLayout('grid')
                            ->helperText(__('shopper::pages/products.variant_images_helpText'))
                            ->collection(config('shopper.media.storage.collection_name'))
                            ->maxSize(config('shopper.media.max_size.images')),
                    ]),

                Components\Separator::make(),

                Components\Section::make(__('shopper::pages/products.pricing.title'))
                    ->description(__('shopper::pages/products.pricing.description'))
                    ->compact()
                    ->aside()
                    ->schema([
                        Forms\Components\Livewire::make(Pricing::class, ['model' => $this->variant])
                            ->dehydrated(false),
                    ]),

                Components\Separator::make(),

                Components\Section::make(__('shopper::pages/settings/menu.location'))
                    ->compact()
                    ->aside()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('sku')
                                    ->label(__('shopper::forms.label.sku'))
                                    ->unique(config('shopper.models.variant'), 'sku', ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('barcode')
                                    ->label(__('shopper::forms.label.barcode'))
                                    ->unique(config('shopper.models.variant'), 'barcode', ignoreRecord: true)
                                    ->maxLength(255),
                            ]),

                        Components\Separator::make(),

                        Forms\Components\Livewire::make(VariantStock::class),
                    ]),
            ])
            ->statePath('data')
            ->model($this->variant);
    }

    public function store(): void
    {
        $this->variant->update($this->form->getState());
        $this->form->model($this->variant)->saveRelationships();

        $this->dispatch('onVariantUpdated');

        Notification::make()
            ->title(__('shopper::pages/products.notifications.variation_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.products.variant')
            ->title(__('shopper::pages/products.variants.variant_title', ['name' => $this->variant->name]));
    }
}
