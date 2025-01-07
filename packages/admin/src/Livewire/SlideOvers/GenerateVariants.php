<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Shopper\Actions\Store\Product\SaveProductVariantsAction;
use Shopper\Core\Macros\Arr;
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

    public array $availableOptions = [];

    public array $variants = [];

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
            ->mapWithKeys(fn ($attribute) => [
                $attribute['name'] => collect($attribute['values'])
                    ->map(fn ($item) => [
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
            ->map(fn (ProductVariant $variant) => [ // @phpstan-ignore-line
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => $variant->prices()->first()?->amount ?: 0,
                'stock' => $variant->stock,
                'values' => $variant->values->mapWithKeys(
                    fn ($value) => [
                        $value->attribute->name => [
                            'id' => $value->id,
                            'value' => $value->value,
                        ],
                    ]
                )->toArray(),
            ])
            ->toArray();

        $this->variants = $this->mapVariantsToProductOptions($optionsValues, $variants);
    }

    #[Computed]
    public function product(): Model
    {
        return (new ProductRepository)->with(['options', 'options.values'])->getById($this->productId);
    }

    public function removeVariant($key): void
    {
        unset($this->variants[$key]);
    }

    public static function panelMaxWidth(): string
    {
        return '5xl';
    }

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

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.generate-variants');
    }
}
