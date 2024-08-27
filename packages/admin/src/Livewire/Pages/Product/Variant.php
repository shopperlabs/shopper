<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Shopper\Components;
use Shopper\Core\Events\Products\Updated as ProductUpdated;
use Shopper\Core\Repositories\ProductRepository;
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

    public function mount(int $product, int $variantId): void
    {
        $this->authorize('edit_products');

        $this->product = (new ProductRepository)->getById($product);
        $this->variant = (new ProductRepository)->getById($variantId);

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
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Set $set): void {
                                        $set('slug', Str::slug($state));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label(__('shopper::forms.label.slug'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(config('shopper.models.product'), 'slug', ignoreRecord: true),

                                Forms\Components\TextInput::make('price_amount') // @phpstan-ignore-line
                                    ->label(__('shopper::forms.label.price_amount'))
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->suffix(shopper_currency())
                                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),

                                Forms\Components\TextInput::make('old_price_amount') // @phpstan-ignore-line
                                    ->label(__('shopper::forms.label.compare_price'))
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->suffix(shopper_currency())
                                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                            ]),
                    ]),

                Components\Separator::make(),

                Components\Section::make(__('shopper::words.images'))
                    ->compact()
                    ->aside()
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                            ->multiple()
                            ->hiddenLabel()
                            ->helperText(__('shopper::pages/products.variant_images_helpText'))
                            ->collection(config('shopper.core.storage.collection_name'))
                            ->maxSize(1024),
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
                                    ->unique(config('shopper.models.product'), 'sku', ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('barcode')
                                    ->label(__('shopper::forms.label.barcode'))
                                    ->unique(config('shopper.models.product'), 'barcode', ignoreRecord: true)
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('security_stock')
                                    ->label(__('shopper::forms.label.safety_stock'))
                                    ->numeric()
                                    ->default(0)
                                    ->rules(['integer', 'min:0']),
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

        event(new ProductUpdated($this->variant));

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
