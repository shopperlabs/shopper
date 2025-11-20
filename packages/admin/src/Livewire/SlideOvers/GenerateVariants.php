<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Shopper\Actions\Store\Product\SaveProductVariantsAction;
use Shopper\Core\Macros\Arr;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Core\Repositories\VariantRepository;
use Shopper\Helpers\MapProductOptions;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property-read Product $product
 */
class GenerateVariants extends SlideOverComponent
{
    #[Locked]
    public int $productId;

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
        $this->setupProductAttributes();
    }

    public function generate(): void
    {
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

        $variants = (new VariantRepository)->query()
            ->with(['prices', 'values', 'prices.currency' => function ($query): void {
                $query->where('code', shopper_currency());
            }])
            ->where('product_id', $this->productId)
            ->get()
            ->map(fn (ProductVariant $variant): array => [ // @phpstan-ignore-line
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => $variant->prices()->first()?->amount ?: 0,
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

    #[Computed]
    public function product(): Product
    {
        /** @var Product */
        return (new ProductRepository)->with(['options', 'options.values'])->getById($this->productId);
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
                    $sku = \Illuminate\Support\Arr::join([
                        $this->product->sku,
                        mb_strtoupper(Str::slug(Arr::performPermutationIntoWord($permutation, 'value', '-'))),
                    ], '-');
                    $price = 0;
                    $stock = 0;
                }
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
