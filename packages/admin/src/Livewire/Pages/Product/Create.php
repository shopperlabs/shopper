<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Shopper\Components;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Repositories\ChannelRepository;
use Shopper\Core\Repositories\Store\ProductRepository;
use Shopper\Core\Traits\Attributes\WithChoicesBrands;
use Shopper\Core\Traits\Attributes\WithSeoAttributes;
use Shopper\Feature;
use Shopper\Livewire\Components\Products\WithAttributes;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Create extends AbstractPageComponent implements HasForms
{
    use InteractsWithForms;
    use WithAttributes;
    use WithChoicesBrands;
    use WithSeoAttributes;

    public $defaultChannel;

    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('add_products');

        $this->form->fill();

        $this->defaultChannel = (new ChannelRepository())
            ->where('is_default', true)
            ->first();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\WizardColumn::make([
                    Components\Wizard\StepColumn::make(__('shopper::words.general'))
                        ->icon('untitledui-file-02')
                        ->schema([
                            Components\Section::make()
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label(__('shopper::layout.forms.label.name'))
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, Forms\Set $set): void {
                                            $set('slug', Str::slug($state));
                                        }),
                                    Forms\Components\TextInput::make('slug')
                                        ->label(__('shopper::layout.forms.label.slug'))
                                        ->disabled()
                                        ->dehydrated()
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(config('shopper.models.product'), 'slug'),

                                    Forms\Components\RichEditor::make('description')
                                        ->label(__('shopper::layout.forms.label.description'))
                                        ->columnSpan('full'),
                                ])
                                ->compact()
                                ->columns(),

                            Components\Separator::make(),

                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\Toggle::make('is_visible')
                                        ->label(__('shopper::layout.forms.label.visible'))
                                        ->helperText(__('shopper::pages/products.visible_help_text'))
                                        ->onColor('success')
                                        ->default(true),

                                    Forms\Components\DateTimePicker::make('published_at')
                                        ->label(__('shopper::layout.forms.label.availability'))
                                        ->native(false)
                                        ->default(now())
                                        ->helperText(__('shopper::pages/products.availability_description'))
                                        ->required(),
                                ]),
                        ]),

                    Components\Wizard\StepColumn::make(__('shopper::words.pricing'))
                        ->icon('untitledui-coins-stacked-02')
                        ->schema([
                            Forms\Components\TextInput::make('price_amount')
                                ->label(__('shopper::layout.forms.label.price_amount'))
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->suffix(shopper_currency())
                                ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),

                            Forms\Components\TextInput::make('old_price_amount')
                                ->label(__('shopper::layout.forms.label.compare_price'))
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->suffix(shopper_currency())
                                ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),

                            Forms\Components\TextInput::make('cost_amount')
                                ->label(__('shopper::layout.forms.label.cost_per_item'))
                                ->helperText(__('shopper::pages/products.cost_per_items_help_text'))
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->suffix(shopper_currency())
                                ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                        ])
                        ->columns(),

                    Components\Wizard\StepColumn::make(__('shopper::words.media'))
                        ->icon('untitledui-image')
                        ->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                                ->collection(config('shopper.core.storage.collection_name'))
                                ->label(__('shopper::words.images'))
                                ->helperText(__('shopper::pages/products.images_helpText'))
                                ->multiple()
                                ->columnSpan(3),
                            Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                                ->collection(config('shopper.core.storage.thumbnail_collection'))
                                ->label(__('shopper::layout.forms.label.thumbnail'))
                                ->helperText(__('shopper::pages/products.thumbnail_helpText'))
                                ->image()
                                ->maxSize(1024)
                                ->imageEditor()
                                ->columnSpan(2),
                        ])
                        ->columns(5),

                    Components\Wizard\StepColumn::make(__('shopper::pages/products.product_associations'))
                        ->icon('untitledui-git-branch')
                        ->schema([
                            Forms\Components\Select::make('brand_id')
                                ->label(__('shopper::layout.forms.label.brand'))
                                ->relationship('brand', 'name', fn (Builder $query) => $query->where('is_enabled', true))
                                ->searchable()
                                ->visible(Feature::enabled('brand')),

                            Forms\Components\Select::make('collections')
                                ->label(__('shopper::layout.sidebar.collections'))
                                ->relationship('collections', 'name', fn (Builder $query) => $query->where('is_enabled', true))
                                ->searchable()
                                ->multiple()
                                ->visible(Feature::enabled('collection')),

                            Components\Form\SelectTree::make('categories')
                                ->relationship(
                                    relationship: 'categories',
                                    titleAttribute: 'name',
                                    parentAttribute: 'parent_id'
                                )
                                ->independent(false)
                                ->enableBranchNode()
                                ->visible(Feature::enabled('category')),
                        ])
                        ->columns()
                        ->visible(
                            Feature::enabled('brand')
                            || Feature::enabled('category')
                            || Feature::enabled('collection')
                        ),

                    Components\Wizard\StepColumn::make(__('shopper::words.location'))
                        ->icon('untitledui-package')
                        ->schema([
                            Forms\Components\Placeholder::make('stock')
                                ->label('Stock & Inventory')
                                ->content(new HtmlString(Blade::render(<<<'BLADE'
                                    <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Configure the inventory and stock for this product') }}
                                    </p>
                                BLADE))),

                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('sku')
                                        ->label(__('shopper::layout.forms.label.sku'))
                                        ->unique(config('shopper.models.product'), 'sku')
                                        ->maxLength(255),

                                    Forms\Components\TextInput::make('barcode')
                                        ->label(__('shopper::layout.forms.label.barcode'))
                                        ->unique(config('shopper.models.product'), 'barcode')
                                        ->maxLength(255),

                                    Forms\Components\TextInput::make('quantity')
                                        ->label(__('shopper::layout.forms.label.quantity'))
                                        ->numeric()
                                        ->rules(['integer', 'min:1']),

                                    Forms\Components\TextInput::make('security_stock')
                                        ->label(__('shopper::layout.forms.label.safety_stock'))
                                        ->helperText(__('shopper::pages/products.safety_security_help_text'))
                                        ->numeric()
                                        ->default(0)
                                        ->rules(['integer', 'min:0']),
                                ])
                                ->columns(),
                        ]),

                    Components\Wizard\StepColumn::make(__('shopper::words.shipping'))
                        ->icon('untitledui-plane')
                        ->schema([
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\Checkbox::make('backorder')
                                        ->label(__('shopper::pages/products.product_can_returned'))
                                        ->helperText(__('shopper::pages/products.product_can_returned_help_text')),

                                    Forms\Components\Checkbox::make('require_shipping')
                                        ->label(__('shopper::pages/products.product_shipped'))
                                        ->helperText(__('shopper::pages/products.product_shipped_help_text'))
                                        ->default(true)
                                        ->live(),
                                ]),

                            Components\Separator::make(),

                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('shipping')
                                        ->label(__('shopper::words.weight_dimension'))
                                        ->content(new HtmlString(Blade::render(<<<'BLADE'
                                            <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                                {{ __('shopper::pages/products.weight_dimension_help_text') }}
                                            </p>
                                        BLADE))),

                                    Forms\Components\Grid::make()
                                        ->schema(Components\Form\ShippingField::make()),
                                ])
                                ->visible(fn (Forms\Get $get): bool => $get('require_shipping')),
                        ]),

                    Components\Wizard\StepColumn::make('Seo & Metadata')
                        ->icon('untitledui-monitor-02')
                        ->schema([
                            Forms\Components\Placeholder::make(__('shopper::pages/products.seo.title'))
                                ->content(__('shopper::pages/products.seo.description'))
                                ->content(new HtmlString(Blade::render(<<<'BLADE'
                                    <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('shopper::pages/products.seo.description') }}
                                    </p>
                                BLADE))),

                            Forms\Components\Group::make()
                                ->schema(Components\Form\SeoField::make()),

                            Components\Separator::make(),

                            Forms\Components\KeyValue::make('metadata')
                                ->reorderable(),
                        ]),
                ])
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                            {{ __('shopper::layout.forms.actions.save') }}
                        </x-shopper::buttons.primary>
                     BLADE)))
                    ->persistStepInQueryString(),
            ])
            ->statePath('data')
            ->model(config('shopper.models.product'));
    }

    public function store(): void
    {
        $data = $this->form->getState();

        /** @var Product $product */
        $product = (new ProductRepository())->create(
            Arr::except($data, ['quantity', 'categories'])
        );
        $this->form->model($product)->saveRelationships();

        $product->channels()->attach($this->defaultChannel->id);

        if ($data['categories'] && count($data['categories']) > 0) {
            $product->categories()->sync($data['categories']);
        }

        $quantity = $data['quantity'];

        /** @var Inventory $inventory */
        $inventory = Inventory::default()->first();

        if ($inventory && $quantity && $quantity > 0) {
            $product->mutateStock(
                inventoryId: $inventory->id,
                quantity: (int) $quantity,
                arguments: [
                    'event' => __('shopper::pages/products.inventory.initial'),
                    'old_quantity' => $quantity,
                ]
            );
        }

        Notification::make()
            ->title(__('shopper::pages/products.notifications.create'))
            ->success()
            ->send();

        $this->redirectRoute(name: 'shopper.products.index', navigate: true);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.products.create')
            ->title(__('shopper::words.actions_label.add_new', ['name' => __('shopper::words.product')]));
    }
}
