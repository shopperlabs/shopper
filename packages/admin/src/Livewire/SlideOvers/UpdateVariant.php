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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Computed;
use Shopper\Components;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Core\Repositories\VariantRepository;
use Shopper\Helpers\MapProductOptions;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property Form $form
 * @property Collection $options
 * @property array $variantsOptions
 */
class UpdateVariant extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    public $variant;

    public $product;

    public ?array $data = [];

    public bool $alert = false;

    public function mount(int $productId, int $variantId): void
    {
        $this->product = (new ProductRepository)->getById($productId);
        $this->variant = (new VariantRepository)->with(['values', 'values.attribute'])->getById($variantId);

        $this->form->fill(array_merge(
            $this->variant->toArray(),
            count($this->variantsOptions)
                ? ['values' => $this->variant->values->mapWithKeys( // @phpstan-ignore-line
                    fn ($value) => [
                        $value->attribute->id => $value->id,
                    ]
                )->toArray()]
                : [],
        ));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->placeholder('Model Y, Model S (Eg. for Tesla)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Checkbox::make('allow_backorder')
                    ->label(__('shopper::pages/products.allow_backorder')),

                Forms\Components\Group::make()
                    ->visible(fn (): bool => count($this->variantsOptions) > 0)
                    ->schema([
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
                            ->visible(fn (Forms\Get $get): bool => $get('values') !== null && $this->alert)
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
                    ]),

                Components\Separator::make(),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Placeholder::make('dimensions')
                            ->label(__('shopper::pages/products.shipping.package_dimension'))
                            ->content(
                                new HtmlString(Blade::render(<<<'BLADE'
                                    <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('shopper::pages/products.shipping.package_dimension_description') }}
                                    </p>
                                BLADE))
                            ),

                        Forms\Components\Grid::make()
                            ->schema(Components\Form\ShippingField::make()),
                    ]),
            ])
            ->statePath('data')
            ->model($this->variant);
    }

    public function save(): void
    {
        $values = data_get($this->form->getState(), 'values');

        if ($values && $this->variantAlreadyExist($values)) {
            $this->alert = true;

            return;
        }

        $this->variant->update(Arr::except($this->form->getState(), 'values'));

        if ($values) {
            $this->variant->values()->sync($values);
        }

        Notification::make()
            ->title(__('shopper::pages/products.notifications.variation_update'))
            ->success()
            ->send();

        $this->redirect(
            route('shopper.products.variant', ['variantId' => $this->variant->id, 'productId' => $this->product->id]),
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
    public function options(): Collection
    {
        return collect(MapProductOptions::generate($this->product));
    }

    #[Computed]
    public function variantsOptions(): array
    {
        return (new VariantRepository)->query()
            ->with('values')
            ->select('product_id', 'id')
            ->where('product_id', $this->product->id)
            ->get()
            ->map(
                fn (ProductVariant $variant): array => $variant->values->pluck('id')->toArray() // @phpstan-ignore-line
            )
            ->reject(fn ($value) => array_diff($value, $this->variant->values->pluck('id')->toArray()) === [])
            ->values()
            ->toArray();
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.update-variant');
    }
}
