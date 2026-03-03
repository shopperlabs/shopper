<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Computed;
use Shopper\Components\Form\ShippingField;
use Shopper\Components\Separator;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Helpers\MapProductOptions;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property-read Schema $form
 * @property-read Collection<string, mixed> $options
 * @property-read array<array-key, mixed> $variantsOptions
 */
class UpdateVariant extends SlideOverComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?ProductVariant $variant = null;

    public ?Product $product = null;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public bool $alert = false;

    public function mount(): void
    {
        $this->authorize('edit_product_variants');

        $this->variant?->load(['values', 'values.attribute']);

        $this->form->fill(array_merge(
            $this->variant?->toArray() ?? [],
            count($this->variantsOptions)
                ? ['values' => $this->variant?->values->mapWithKeys(
                    fn (AttributeValue $value): array => [
                        $value->attribute->id => $value->id,
                    ]
                )->toArray() ?? []]
                : [],
        ));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->placeholder('Model Y, Model S (Eg. for Tesla)')
                    ->required()
                    ->maxLength(255),
                Checkbox::make('allow_backorder')
                    ->label(__('shopper::pages/products.allow_backorder')),
                Group::make()
                    ->visible(fn (): bool => count($this->variantsOptions) > 0)
                    ->schema([
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
                        Placeholder::make('alert')
                            ->visible(fn (Get $get): bool => $get('values') !== null && $this->alert)
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
                Separator::make(),
                Group::make()
                    ->schema([
                        Placeholder::make('dimensions')
                            ->label(__('shopper::pages/products.shipping.package_dimension'))
                            ->content(
                                new HtmlString(Blade::render(<<<'BLADE'
                                    <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('shopper::pages/products.shipping.package_dimension_description') }}
                                    </p>
                                BLADE))
                            ),
                        Grid::make()
                            ->schema(ShippingField::make()),
                    ]),
            ])
            ->statePath('data')
            ->model($this->variant); // @phpstan-ignore-line
    }

    public function save(): void
    {
        $this->authorize('edit_product_variants');

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
            route('shopper.products.variant', ['product' => $this->product, 'variant' => $this->variant]),
            navigate: true
        );
    }

    /**
     * @return Collection<string, mixed>
     */
    #[Computed]
    public function options(): Collection
    {
        return collect(MapProductOptions::generate($this->product));
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
            ->where('product_id', $this->product?->id)
            ->get()
            ->map(
                fn (ProductVariant $variant): array => $variant->values->pluck('id')->toArray() // @phpstan-ignore-line
            )
            ->reject(fn ($value): bool => array_diff($value, $this->variant?->values->pluck('id')->toArray() ?? []) === [])
            ->values()
            ->toArray();
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.update-variant');
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
