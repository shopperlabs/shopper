<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Livewire\Attributes\Locked;
use Shopper\Actions\Store\Product\SaveProductVariantsAction;
use Shopper\Components\Form\MoneyInput;
use Shopper\Core\Macros\Arr;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Contracts\ProductVariant as ProductVariantContract;
use Shopper\Helpers\MapProductOptions;
use Shopper\Traits\HandlesAuthorizationExceptions;

class GenerateVariants extends SlideOverComponent
{
    use HandlesAuthorizationExceptions;

    #[Locked]
    public ProductContract $product;

    /** @var array<string, mixed> */
    public array $availableOptions = [];

    /** @var array<string, mixed> */
    public array $variants = [];

    public static function panelMaxWidth(): string
    {
        return '5xl';
    }

    public function mount(): void
    {
        $this->authorize('products.variants.edit');

        $this->product->loadMissing(['options', 'options.values']);

        $this->setupProductAttributes();
    }

    public function generate(): void
    {
        $this->authorize('products.variants.edit');

        $keptIds = collect($this->variants)->pluck('variant_id')->filter()->all();

        if ($this->product->variants()->whereKeyNot($keptIds)->exists()) {
            $this->authorize('products.variants.delete');
        }

        if (collect($this->variants)->contains(fn (array $variant): bool => ! ($variant['variant_id'] ?? null))) {
            $this->authorize('products.variants.create');
        }

        $this->validate(
            [
                'variants.*.name' => ['required', 'string', 'max:255'],
                'variants.*.sku' => ['nullable', 'string', 'max:255', 'distinct:ignore_case'],
                'variants.*.price' => ['nullable', 'regex:'.MoneyInput::amountRegex(shopper_currency())],
                'variants.*.stock' => Rule::forEach(
                    fn (mixed $stock, string $attribute, array $data, array $variant): array => ($variant['variant_id'] ?? null) ? [] : ['nullable', 'integer', 'min:0']
                ),
                'variants.*.values.*' => [Rule::in($this->product->options()->pluck('attribute_value_id')->filter()->all())],
            ],
            attributes: [
                'variants.*.name' => __('shopper::forms.label.name'),
                'variants.*.sku' => __('shopper::forms.label.sku'),
                'variants.*.price' => __('shopper::forms.label.price'),
                'variants.*.stock' => __('shopper::forms.label.stock_number_value'),
                'variants.*.values.*' => __('shopper::forms.label.value'),
            ],
        );

        $this->variants = app()->call(SaveProductVariantsAction::class, [
            'product' => $this->product,
            'variants' => $this->variants,
        ]);

        Notification::make()
            ->title(__('shopper::pages/products.notifications.variation_generate'))
            ->success()
            ->send();

        $this->redirect(
            route('shopper.products.edit', ['product' => $this->product, 'tab' => 'variants']),
            navigate: true
        );
    }

    public function setupProductAttributes(): void
    {
        $this->availableOptions = MapProductOptions::generate($this->product);

        $this->mapVariantPermutations();
    }

    public function mapVariantPermutations(): void
    {
        $optionsValues = collect($this->availableOptions)
            ->mapWithKeys(fn (array $attribute): array => [
                $attribute['name'] => collect($attribute['values']) // @phpstan-ignore-line
                    ->map(fn (array $item): array => [
                        'id' => $item['id'],
                        'value' => $item['value'],
                    ]),
            ])
            ->toArray();

        $unit = is_no_division_currency(shopper_currency()) ? 1 : 100;

        $variants = resolve(ProductVariantContract::class)::query()
            ->with(['values', 'prices' => function ($query): void {
                $query->where('currency_id', shopper_setting('default_currency_id'));
            }])
            ->where('product_id', $this->product->id)
            ->get()
            ->map(fn (ProductVariantContract $variant): array => [ // @phpstan-ignore-line
                'id' => $variant->id,
                'sku' => $variant->sku,
                'name' => $variant->name,
                'price' => ($variant->prices->first()->amount ?? 0) / $unit,
                'stock' => $variant->stock,
                'values' => $variant->values->mapWithKeys(
                    fn (AttributeValue $value): array => [
                        $value->attribute->name => [
                            'id' => $value->id,
                            'value' => $value->value,
                        ],
                    ]
                )->toArray(),
            ])
            ->toArray();

        $this->variants = $this->mapVariantsToProductOptions($optionsValues, $variants); // @phpstan-ignore-line
    }

    public function removeVariant(string|int $key): void
    {
        unset($this->variants[$key]);
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.generate-variants');
    }

    /**
     * @param  array<array-key, mixed>  $options
     * @param  array<array-key, mixed>  $variants
     * @return list<array<string, mixed>>
     */
    protected function mapVariantsToProductOptions(array $options, array $variants): array
    {
        $permutations = Arr::permutate($options);
        $carried = collect($variants)->flatMap(fn (array $variant): array => array_keys($variant['values']))->unique()->all();
        $valueless = collect($variants)->where('values', [])->count();
        $matches = [];

        foreach ([true, false] as $exact) {
            foreach ($permutations as $index => $permutation) {
                if (isset($matches[$index])) {
                    continue;
                }

                $name = Arr::performPermutationIntoWord($permutation, 'value');

                $variantIndex = collect($variants)
                    ->except(array_column($matches, 'variant'))
                    ->search(function (array $variant) use ($permutation, $carried, $valueless, $name, $exact): bool {
                        $missing = Arr::recursiveArrayDiffAssoc($permutation, $variant['values']);

                        if ($missing === []) {
                            return true;
                        }

                        $fits = count($permutation) - count($missing) === count($variant['values'])
                            && array_intersect(array_keys($missing), $carried) === [];

                        return $fits && ($exact ? $variant['name'] === $name : $variant['values'] !== [] || $valueless === 1);
                    });

                if ($variantIndex !== false) {
                    $matches[$index] = ['variant' => $variantIndex, 'exact' => $exact];
                }
            }
        }

        return collect($permutations)->map(function (array $permutation, int $index) use ($matches, $variants): array {
            $match = $matches[$index] ?? null;
            $variant = $match ? $variants[$match['variant']] : null;
            $sku = $variant['sku'] ?? null;

            if ($sku === null) {
                $variantSlug = mb_strtoupper(Str::slug(Arr::performPermutationIntoWord($permutation, 'value', '-')));
                $sku = $this->product->sku
                    ? "{$this->product->sku}-{$variantSlug}"
                    : $variantSlug;
            }

            return [
                'key' => Str::random(),
                'variant_id' => $variant['id'] ?? null,
                'name' => ($match['exact'] ?? false) ? $variant['name'] : Arr::performPermutationIntoWord($permutation, 'value'),
                'sku' => $sku,
                'price' => $variant['price'] ?? 0,
                'stock' => $variant['stock'] ?? 0,
                'values' => Arr::getPermutationIds($permutation),
            ];
        })->all();
    }
}
