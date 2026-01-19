<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Unique;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Actions\Store\Product\CreateNewVariant;
use Shopper\Components\Form\CurrenciesField;
use Shopper\Components\SlideOverWizard;
use Shopper\Components\Wizard\StepColumn;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Currency;
use Shopper\Helpers\MapProductOptions;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property-read Schema $form
 * @property-read Collection<int, Currency> $currencies
 * @property-read Collection<string, mixed> $options
 * @property-read array<array-key, mixed> $variantsOptions
 */
class AddVariant extends SlideOverComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    #[Locked]
    public Product $product;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function panelMaxWidth(): string
    {
        return '5xl';
    }

    public function mount(): void
    {
        $this->authorize('add_products');

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SlideOverWizard::make()
                    ->schema([
                        StepColumn::make(__('shopper::words.general'))
                            ->icon(Untitledui::File02)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('shopper::forms.label.name'))
                                    ->placeholder('Model Y, Model S (Eg. for Tesla)')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('sku')
                                    ->label(__('shopper::forms.label.sku'))
                                    ->unique(
                                        table: shopper_table('product_variants'),
                                        column: 'sku',
                                        modifyRuleUsing: fn (Unique $rule): Unique => $rule->where('product_id', $this->product->id)
                                    )
                                    ->maxLength(255),
                                Group::make()
                                    ->visible(fn (): bool => $this->options->isNotEmpty())
                                    ->schema([
                                        TextEntry::make('options')
                                            ->label(__('shopper::pages/products.modals.variants.options.title'))
                                            ->state(
                                                new HtmlString(Blade::render(<<<'BLADE'
                                                    <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                                        {{ __('shopper::pages/products.modals.variants.options.description') }}
                                                    </p>
                                                BLADE))
                                            ),
                                        Group::make()
                                            ->schema(
                                                $this->options->map(
                                                    fn (array $option): Select => Select::make('values.'.$option['id'])
                                                        ->label($option['name'])
                                                        ->key($option['key'])
                                                        ->required()
                                                        ->searchable()
                                                        ->optionsLimit(10)
                                                        ->options(
                                                            collect($option['values'])->mapWithKeys(
                                                                fn (array $value): array => [$value['id'] => $value['value']]
                                                            )
                                                        )
                                                        ->native(false)
                                                )->toArray()
                                            )
                                            ->columns(3),
                                        TextEntry::make('alert')
                                            ->visible(fn (Get $get): bool => $get('values') !== null && $this->variantAlreadyExist($get('values')))
                                            ->hiddenLabel()
                                            ->state(
                                                new HtmlString(Blade::render(<<<'BLADE'
                                                    <div class="p-1 max-w-xl">
                                                        <x-shopper::alert
                                                            icon="phosphor-swatches-duotone"
                                                            :message="__('shopper::pages/products.notifications.variant_already_exists')"
                                                        />
                                                    </div>
                                                BLADE))
                                            )
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->afterValidation(function (Get $get): void {
                                if ($get('values') !== null && $this->variantAlreadyExist($get('values'))) {
                                    throw new Halt;
                                }
                            }),
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
                                    ->helperText(__('shopper::pages/products.variant_images_helpText'))
                                    ->multiple()
                                    ->panelLayout('grid')
                                    ->maxSize(config('shopper.media.max_size.images'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(5),
                        StepColumn::make(__('shopper::words.pricing'))
                            ->icon(Untitledui::CoinsStacked02)
                            ->schema(CurrenciesField::make($this->currencies))
                            ->statePath('prices'),
                        StepColumn::make(__('shopper::pages/settings/menu.location'))
                            ->icon(Untitledui::Package)
                            ->schema([
                                TextEntry::make('stock')
                                    ->label(__('shopper::pages/products.stock_inventory_heading'))
                                    ->state(new HtmlString(Blade::render(<<<'BLADE'
                                        <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('shopper::pages/products.stock_inventory_description', ['item' => __('shopper::pages/products.variants.single')]) }}
                                        </p>
                                    BLADE))),
                                Grid::make()
                                    ->schema([
                                        TextInput::make('barcode')
                                            ->label(__('shopper::forms.label.barcode'))
                                            ->unique(shopper_table('product_variants'), 'barcode')
                                            ->maxLength(255),
                                        TextInput::make('quantity')
                                            ->label(__('shopper::forms.label.quantity'))
                                            ->numeric()
                                            ->rules(['integer', 'min:0']),
                                    ])
                                    ->columns(3),
                            ]),
                    ])
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button type="submit">
                            {{ __('shopper::forms.actions.save') }}
                        </x-filament::button>
                     BLADE))),
            ])
            ->statePath('data')
            ->model(config('shopper.models.variant'));
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (isset($data['values']) && $this->variantAlreadyExist($data['values'])) {
            Notification::make()
                ->title(__('shopper::pages/products.notifications.variant_already_exists'))
                ->warning()
                ->send();

            return;
        }

        /** @var Model&ProductVariant $variant */
        $variant = app()->call(CreateNewVariant::class, [
            'data' => array_merge($data, ['product_id' => $this->product->id]),
        ]);

        $this->form->model($variant)->saveRelationships();

        Notification::make()
            ->title(__('shopper::layout.status.added'))
            ->body(__('shopper::pages/products.notifications.variation_create'))
            ->success()
            ->send();

        $this->redirect(
            route('shopper.products.variant', ['product' => $this->product, 'variant' => $variant]),
            navigate: true
        );
    }

    /**
     * @return Collection<int, Currency>
     */
    #[Computed]
    public function currencies(): Collection
    {
        /** @var Collection<int, Currency> */
        return Currency::query()
            ->select('id', 'name', 'code', 'symbol')
            ->whereIn('id', shopper_setting('currencies'))
            ->get();
    }

    /**
     * @return array<array-key, mixed>
     */
    #[Computed]
    public function variantsOptions(): array
    {
        return resolve(ProductVariant::class)::query()
            ->with('values')
            ->select('product_id', 'id')
            ->where('product_id', $this->product->id)
            ->get()
            ->map(
                fn (ProductVariant $variant): array => $variant->values->pluck('id')->toArray() // @phpstan-ignore-line
            )
            ->toArray();
    }

    /**
     * @return \Illuminate\Support\Collection<string, mixed>
     */
    #[Computed]
    public function options(): \Illuminate\Support\Collection
    {
        return collect(MapProductOptions::generate($this->product));
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.add-variant');
    }

    /**
     * @param  array<array-key, mixed>  $optionsValues
     */
    protected function variantAlreadyExist(array $optionsValues = []): bool
    {
        foreach ($this->variantsOptions as $option) {
            if (array_diff(array_values($optionsValues), $option) === []) {
                return true;
            }
        }

        return false;
    }
}
