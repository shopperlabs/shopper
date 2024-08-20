<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Shopper\Actions\Store\InitialQuantityInventory;
use Shopper\Components;
use Shopper\Core\Models\Product;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property Forms\Form $form
 */
class AddVariantForm extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    public int $productId;

    public ?array $data = [];

    public function mount(int $productId): void
    {
        $this->authorize('add_products');

        $this->productId = $productId;

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('parent_id')
                    ->default($this->productId),

                Components\Section::make(__('shopper::words.general'))
                    ->collapsible()
                    ->compact()
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
                                    ->unique(config('shopper.models.product'), 'slug'),
                            ]),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
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
                    ]),

                Components\Section::make(__('shopper::pages/settings/menu.location'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Forms\Components\Placeholder::make('stock')
                            ->label(__('shopper::pages/products.stock_inventory_heading'))
                            ->content(new HtmlString(Blade::render(<<<'BLADE'
                                <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/products.stock_inventory_description', ['item' => __('shopper::pages/products.variants.single')]) }}
                                </p>
                            BLADE))),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('sku')
                                    ->label(__('shopper::forms.label.sku'))
                                    ->unique(config('shopper.models.product'), 'sku')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('barcode')
                                    ->label(__('shopper::forms.label.barcode'))
                                    ->unique(config('shopper.models.product'), 'barcode')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('quantity')
                                    ->label(__('shopper::forms.label.quantity'))
                                    ->numeric()
                                    ->required()
                                    ->rules(['integer', 'min:1']),

                                Forms\Components\TextInput::make('security_stock')
                                    ->label(__('shopper::forms.label.safety_stock'))
                                    ->numeric()
                                    ->default(0)
                                    ->rules(['integer', 'min:0']),
                            ])
                            ->columns(),
                    ]),

                Components\Section::make(__('shopper::words.media'))
                    ->collapsed()
                    ->compact()
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                            ->multiple()
                            ->helperText(__('shopper::pages/products.variant_images_helpText'))
                            ->collection(config('shopper.core.storage.collection_name'))
                            ->maxSize(1024),
                    ]),

                Components\Section::make(__('shopper::words.shipping'))
                    ->collapsed()
                    ->compact()
                    ->schema([
                        Forms\Components\Placeholder::make('shipping')
                            ->label(__('shopper::pages/products.weight_dimension'))
                            ->content(new HtmlString(Blade::render(<<<'BLADE'
                                <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/products.weight_dimension_help_text') }}
                                </p>
                            BLADE))),

                        Forms\Components\Grid::make()
                            ->schema(Components\Form\ShippingField::make()),
                    ]),

                Components\Section::make('Metadata')
                    ->collapsed()
                    ->compact()
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->hiddenLabel()
                            ->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model(config('shopper.models.product'));
    }

    public function save(): void
    {
        $data = $this->form->getState();

        /** @var Product $product */
        $product = (new ProductRepository)->create(
            Arr::except($data, ['quantity'])
        );
        $this->form->model($product)->saveRelationships();

        $quantity = (int) $data['quantity'];

        if ($quantity && $quantity > 0) {
            (new InitialQuantityInventory)->handle($quantity, $product);
        }

        Notification::make()
            ->title(__('shopper::layout.status.added'))
            ->body(__('shopper::pages/products.notifications.variation_create'))
            ->success()
            ->send();

        $this->dispatch('onVariantsUpdated');

        $this->closePanel();
    }

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.add-variant');
    }
}
