<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Shopper\Actions\Store\Product\CreateNewVariant;
use Shopper\Components;
use Shopper\Components\Form\CurrenciesField;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Core\Repositories\VariantRepository;
use Shopper\Helpers\MapProductOptions;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property Forms\Form $form
 * @property Collection $currencies
 * @property Collection $options
 * @property array $variantsOptions
 */
class AddVariant extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    #[Locked]
    public int $productId;

    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('add_products');

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\SlideOverWizard::make()
                    ->schema([
                        Components\Wizard\StepColumn::make(__('shopper::words.general'))
                            ->icon('untitledui-file-02')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('shopper::forms.label.name'))
                                    ->placeholder('Model Y, Model S (Eg. for Tesla)')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('sku')
                                    ->label(__('shopper::forms.label.sku'))
                                    ->unique(shopper_table('product_variants'), 'sku')
                                    ->maxLength(255),

                                Forms\Components\Group::make()
                                    ->visible(fn (): bool => count($this->variantsOptions) > 0)
                                    ->schema([
                                        Forms\Components\Placeholder::make('options')
                                            ->label(__('shopper::pages/products.modals.variants.options.title'))
                                            ->content(
                                                new HtmlString(Blade::render(<<<'BLADE'
                                                    <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                                        {{ __('shopper::pages/products.modals.variants.options.description') }}
                                                    </p>
                                                BLADE))
                                            ),

                                        Forms\Components\Group::make()
                                            ->schema(
                                                $this->options->map(
                                                    fn ($option): Forms\Components\Select => Forms\Components\Select::make('values.' . $option['id'])
                                                        ->label($option['name'])
                                                        ->key($option['key'])
                                                        ->required()
                                                        ->searchable()
                                                        ->optionsLimit(10)
                                                        ->options(
                                                            collect($option['values'])->mapWithKeys(
                                                                fn ($value): array => [$value['id'] => $value['value']]
                                                            )
                                                        )
                                                        ->native(false)
                                                )->toArray()
                                            )
                                            ->columns(3),

                                        Forms\Components\Placeholder::make('alert')
                                            ->visible(fn (Forms\Get $get): bool => $get('values') !== null && $this->variantAlreadyExist($get('values')))
                                            ->hiddenLabel()
                                            ->content(
                                                new HtmlString(Blade::render(<<<'BLADE'
                                                    <x-shopper::alert
                                                        icon="phosphor-swatches-duotone"
                                                        :message="__('shopper::pages/products.notifications.variant_already_exists')"
                                                    />
                                                BLADE))
                                            )
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->afterValidation(function (Forms\Get $get): void {
                                if ($get('values') !== null && $this->variantAlreadyExist($get('values'))) {
                                    throw new Halt;
                                }
                            }),

                        Components\Wizard\StepColumn::make(__('shopper::words.media'))
                            ->icon('untitledui-image')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                                    ->label(__('shopper::forms.label.thumbnail'))
                                    ->helperText(__('shopper::pages/products.thumbnail_helpText'))
                                    ->image()
                                    ->maxSize(config('shopper.media.max_size.thumbnail'))
                                    ->columnSpan(['lg' => 2]),
                                Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                                    ->collection(config('shopper.media.storage.collection_name'))
                                    ->label(__('shopper::words.images'))
                                    ->helperText(__('shopper::pages/products.variant_images_helpText'))
                                    ->multiple()
                                    ->panelLayout('grid')
                                    ->maxSize(config('shopper.media.max_size.images'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(5),

                        Components\Wizard\StepColumn::make(__('shopper::words.pricing'))
                            ->icon('untitledui-coins-stacked-02')
                            ->schema(CurrenciesField::make($this->currencies))
                            ->statePath('prices'),

                        Components\Wizard\StepColumn::make(__('shopper::pages/settings/menu.location'))
                            ->icon('untitledui-package')
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
                                        Forms\Components\TextInput::make('barcode')
                                            ->label(__('shopper::forms.label.barcode'))
                                            ->unique(shopper_table('product_variants'), 'barcode')
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('quantity')
                                            ->label(__('shopper::forms.label.quantity'))
                                            ->numeric()
                                            ->rules(['integer', 'min:0']),
                                    ])
                                    ->columns(3),
                            ]),
                    ])
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                            {{ __('shopper::forms.actions.save') }}
                        </x-shopper::buttons.primary>
                     BLADE))),
            ])
            ->statePath('data')
            ->model(config('shopper.models.variant'));
    }

    public function save(): void
    {
        $data = $this->form->getState();

        /** @var ProductVariant $variant */
        $variant = app()->call(CreateNewVariant::class, [
            'state' => array_merge($data, ['product_id' => $this->productId]),
        ]);

        $this->form->model($variant)->saveRelationships();

        Notification::make()
            ->title(__('shopper::layout.status.added'))
            ->body(__('shopper::pages/products.notifications.variation_create'))
            ->success()
            ->send();

        $this->redirect(
            route('shopper.products.variant', ['variantId' => $variant->id, 'productId' => $this->productId]),
            navigate: true
        );
    }

    protected function variantAlreadyExist(array $optionsValues = []): bool
    {
        foreach ($this->variantsOptions as $option) {
            if (array_diff(array_values($optionsValues), $option) === []) {
                return true;
            }
        }

        return false;
    }

    #[Computed]
    public function currencies(): Collection
    {
        return Currency::query()
            ->select('id', 'name', 'code', 'symbol')
            ->whereIn(
                column: 'id',
                values: shopper_setting('currencies')
            )
            ->get();
    }

    #[Computed]
    public function variantsOptions(): array
    {
        return (new VariantRepository)->query()
            ->with('values')
            ->select('product_id', 'id')
            ->where('product_id', $this->productId)
            ->get()
            ->map(
                fn (ProductVariant $variant): array => $variant->values->pluck('id')->toArray() // @phpstan-ignore-line
            )
            ->toArray();
    }

    #[Computed]
    public function options(): Collection
    {
        /** @var Product $product */
        $product = (new ProductRepository)->getById($this->productId);

        return collect(MapProductOptions::generate($product));
    }

    public static function panelMaxWidth(): string
    {
        return '5xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.add-variant');
    }
}
