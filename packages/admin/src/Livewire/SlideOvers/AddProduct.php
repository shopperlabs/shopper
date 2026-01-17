<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Actions\Store\Product\CreateProductAction;
use Shopper\Components\Separator;
use Shopper\Components\SlideOverWizard;
use Shopper\Components\Wizard\StepColumn;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Contracts\Channel;
use Shopper\Feature;
use Shopper\Livewire\Components\Products\ProductTypeConfiguration;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property-read Schema $form
 */
class AddProduct extends SlideOverComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public int $startStep = 1;

    public ?string $currentProductType = null;

    public static function panelMaxWidth(): string
    {
        return '5xl';
    }

    public function mount(): void
    {
        $this->authorize('add_products');

        $this->currentProductType = shopper_setting('default_product_type');

        $this->startStep = $this->currentProductType ? 2 : 1;

        $this->form->fill(array_merge([
            'channels' => resolve(Channel::class)::query()
                ->where('is_default', true)
                ->pluck('id')
                ->toArray(),
            'published_at' => now(),
            'is_visible' => true,
        ], $this->currentProductType ? ['type' => $this->currentProductType] : []));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SlideOverWizard::make([
                    StepColumn::make(__('shopper::forms.label.type'))
                        ->icon(Untitledui::Dataflow04)
                        ->schema([
                            RadioDeck::make('type')
                                ->options(ProductType::class)
                                ->descriptions(ProductType::class)
                                ->icons(ProductType::class)
                                ->alignment(Alignment::Start)
                                // ->color('primary')
                                ->columns(3)
                                ->live()
                                ->required(),
                            Livewire::make(ProductTypeConfiguration::class, fn (Get $get): array => [
                                'defaultProductType' => $get('type'),
                            ]),
                        ]),

                    StepColumn::make(__('shopper::words.general'))
                        ->icon(Untitledui::File02)
                        ->extraAttributes([
                            'class' => 'w-full max-w-3xl mx-auto',
                        ])
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('name')
                                        ->label(__('shopper::forms.label.name'))
                                        ->placeholder('Table set')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (?string $state, Set $set): void {
                                            if ($state) {
                                                $set('slug', Str::slug($state));
                                            }
                                        }),
                                    TextInput::make('slug')
                                        ->label(__('shopper::forms.label.slug'))
                                        ->placeholder('table-set')
                                        ->disabled()
                                        ->dehydrated()
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(config('shopper.models.product'), 'slug'),
                                    Textarea::make('summary')
                                        ->label(__('shopper::forms.label.summary'))
                                        ->rows(4)
                                        ->columnSpanFull(),
                                    RichEditor::make('description')
                                        ->label(__('shopper::forms.label.description'))
                                        ->columnSpanFull(),
                                ]),
                            Separator::make(),
                            Grid::make()
                                ->schema([
                                    Toggle::make('is_visible')
                                        ->label(__('shopper::forms.label.visible'))
                                        ->helperText(__('shopper::pages/products.visible_help_text')),
                                    DateTimePicker::make('published_at')
                                        ->label(__('shopper::forms.label.availability'))
                                        ->native(false)
                                        ->minDate(now()->subHour())
                                        ->helperText(__('shopper::pages/products.availability_description'))
                                        ->required(),
                                ]),
                        ]),
                    StepColumn::make(__('shopper::pages/products.product_associations'))
                        ->icon(Untitledui::GitBranch)
                        ->extraAttributes([
                            'class' => 'w-full max-w-3xl mx-auto',
                        ])
                        ->schema([
                            Select::make('brand_id')
                                ->label(__('shopper::forms.label.brand'))
                                ->relationship(
                                    name: 'brand',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn (Builder $query) => $query->where('is_enabled', true)
                                )
                                ->searchable()
                                ->optionsLimit(10)
                                ->preload()
                                ->visible(Feature::enabled('brand')),
                            SelectTree::make('categories')
                                ->label(__('shopper::pages/categories.menu'))
                                ->enableBranchNode()
                                ->relationship(
                                    relationship: 'categories',
                                    titleAttribute: 'name',
                                    parentAttribute: 'parent_id',
                                    modifyQueryUsing: fn (Builder $query) => $query->where('is_enabled', true)
                                )
                                ->searchable()
                                ->visible(Feature::enabled('category'))
                                ->withCount(),
                            Select::make('channels')
                                ->label(__('shopper::pages/settings/menu.sales'))
                                ->relationship(
                                    name: 'channels',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn (Builder $query) => $query->where('is_enabled', true)
                                )
                                ->searchable()
                                ->preload()
                                ->multiple(),
                            Select::make('collections')
                                ->label(__('shopper::pages/collections.menu'))
                                ->relationship('collections', 'name')
                                ->searchable()
                                ->preload()
                                ->multiple()
                                ->optionsLimit(10)
                                ->visible(Feature::enabled('collection')),
                        ])
                        ->columns()
                        ->visible(
                            Feature::enabled('brand')
                            || Feature::enabled('category')
                            || Feature::enabled('collection')
                        ),
                    StepColumn::make(__('shopper::words.media'))
                        ->icon(Untitledui::Image)
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('thumbnail')
                                ->collection(config('shopper.media.storage.thumbnail_collection'))
                                ->label(__('shopper::forms.label.thumbnail'))
                                ->helperText(__('shopper::pages/products.thumbnail_helpText'))
                                ->image()
                                ->maxSize(config('shopper.media.max_size.thumbnail'))
                                ->columnSpan(['lg' => 2]),
                            SpatieMediaLibraryFileUpload::make('images')
                                ->collection(config('shopper.media.storage.collection_name'))
                                ->label(__('shopper::words.images'))
                                ->helperText(__('shopper::pages/products.images_helpText'))
                                ->multiple()
                                ->panelLayout('grid')
                                ->maxSize(config('shopper.media.max_size.images'))
                                ->columnSpanFull(),
                        ])
                        ->columns(5),
                    StepColumn::make(__('shopper::pages/products.stock_inventory_heading'))
                        ->icon(Untitledui::Package)
                        ->schema([
                            TextEntry::make('stock')
                                ->label(__('shopper::pages/products.stock_inventory_heading'))
                                ->state(new HtmlString(Blade::render(<<<'BLADE'
                                    <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('shopper::pages/products.stock_inventory_description', ['item' => __('shopper::pages/products.single')]) }}
                                    </p>
                                BLADE))),
                            Grid::make()
                                ->schema([
                                    TextInput::make('sku')
                                        ->label(__('shopper::forms.label.sku'))
                                        ->unique(config('shopper.models.product'), 'sku')
                                        ->maxLength(255),
                                    TextInput::make('barcode')
                                        ->label(__('shopper::forms.label.barcode'))
                                        ->unique(config('shopper.models.product'), 'barcode')
                                        ->maxLength(255),
                                    TextInput::make('quantity')
                                        ->label(__('shopper::forms.label.quantity'))
                                        ->numeric()
                                        ->rules(['integer', 'min:0']),
                                    TextInput::make('security_stock')
                                        ->label(__('shopper::forms.label.safety_stock'))
                                        ->helperText(__('shopper::pages/products.safety_security_help_text'))
                                        ->numeric()
                                        ->default(0)
                                        ->rules(['integer', 'min:0']),
                                ])
                                ->columns(),
                        ]),
                ])
                    ->startOnStep($this->startStep)
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button type="submit" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                            {{ __('shopper::forms.actions.save') }}
                        </x-filament::button>
                     BLADE)))
                    ->persistStepInQueryString(),
            ])
            ->statePath('data')
            ->model(config('shopper.models.product'));
    }

    public function store(): void
    {
        $this->validate();

        $product = app()->call(CreateProductAction::class, [
            'values' => $this->form->getState(),
        ]);

        $this->form->model($product)->saveRelationships();

        Notification::make()
            ->title(__('shopper::notifications.create', ['item' => $product->name]))
            ->success()
            ->send();

        $this->redirectRoute(
            name: 'shopper.products.edit',
            parameters: ['product' => $product],
            navigate: true
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.add-product-form');
    }
}
