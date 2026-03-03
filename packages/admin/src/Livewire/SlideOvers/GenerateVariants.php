<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Shopper\Actions\Store\Product\SaveProductVariantsAction;
use Shopper\Core\Macros\Arr;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Contracts\ProductVariant as ProductVariantContract;
use Shopper\Helpers\MapProductOptions;
use Shopper\Livewire\Components\SlideOverComponent;

class GenerateVariants extends SlideOverComponent
{
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
        $this->authorize('edit_product_variants');

        $this->product->loadMissing(['options', 'options.values']);

        $this->setupProductAttributes();
    }

    public function generate(): void
    {
        $this->authorize('edit_product_variants');

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

        $variants = resolve(ProductVariantContract::class)::query()
            ->with(['prices', 'values', 'prices.currency' => function ($query): void {
                $query->where('code', shopper_currency());
            }])
            ->where('product_id', $this->product->id)
            ->get()
            ->map(fn (ProductVariantContract $variant): array => [ // @phpstan-ignore-line
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => $variant->prices()->first()?->amount ?: 0, // @phpstan-ignore property.notFound
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

        if (count($options) === 1) {
            $newPermutations = [];

            foreach ($permutations as $p) {
                $newPermutations[] = [
                    array_key_first($options) => $p,
                ];
            }

            $permutations = $newPermutations;
        }

        $variantPermutations = [];

        foreach ($permutations as $permutation) {
            $variantIndex = collect($variants)->search(function ($variant) use ($permutation) {
                $valueDifference = Arr::recursiveArrayDiffAssoc($permutation, $variant['values']);

                if (! count($valueDifference)) {
                    return $variant;
                }

                $amountMatched = count($permutation) - count($valueDifference);

                return $amountMatched === count($variant['values']);
            });

            $variant = $variants[$variantIndex] ?? null;

            $variantId = $variant['id'] ?? null;
            $name = $variant['name'] ?? Arr::performPermutationIntoWord($permutation, 'value');
            $sku = $variant['sku'] ?? null;
            $price = $variant['price'] ?? 0;
            $stock = $variant['stock'] ?? 0;

            if ($variant) {
                $existing = collect($variantPermutations)
                    ->where('variant_id', $variant['id'])
                    ->first();

                if ($existing) {
                    $variantId = null;
                    $sku = null;
                    $price = 0;
                    $stock = 0;
                }
            }

            if ($sku === null) {
                $variantSlug = mb_strtoupper(Str::slug(Arr::performPermutationIntoWord($permutation, 'value', '-')));
                $sku = $this->product->sku
                    ? "{$this->product->sku}-{$variantSlug}"
                    : $variantSlug;
            }

            $variantPermutations[] = [
                'key' => Str::random(),
                'variant_id' => $variantId,
                'name' => $name,
                'sku' => $sku,
                'price' => $price,
                'stock' => $stock,
                'values' => Arr::getPermutationIds($permutation),
            ];
        }

        return $variantPermutations;
    }
}
