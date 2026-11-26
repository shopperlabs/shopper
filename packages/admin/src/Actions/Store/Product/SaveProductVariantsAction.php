<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Shopper\Actions\Store\InitialQuantityInventory;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;
use Throwable;

final class SaveProductVariantsAction
{
    /**
     * @param  array<string, mixed>  $variants
     * @param  Model&Product  $product
     * @return array<string, mixed>
     *
     * @throws Throwable
     */
    public function __invoke(array $variants, Product $product): array
    {
        $variants = resolve(Product::class)::withoutSyncingToSearch(fn (): array => $this->saveVariants($variants, $product));

        resolve(Product::class)::query()->whereKey($product->getKey())->searchable();

        return $variants;
    }

    /**
     * @param  array<string, mixed>  $variants
     * @param  Model&Product  $product
     * @return array<string, mixed>
     *
     * @throws Throwable
     */
    private function saveVariants(array $variants, Product $product): array
    {
        DB::beginTransaction();

        try {
            // Delete variants that are no longer in the list
            $existingVariantIds = collect($variants)
                ->pluck('variant_id')
                ->filter()
                ->values();

            /** @var Collection<int, Model&ProductVariant> $variantsToDelete */
            $variantsToDelete = $product->variants()
                ->when($existingVariantIds->isNotEmpty(), fn ($query): mixed => $query->whereNotIn('id', $existingVariantIds))
                ->when($existingVariantIds->isEmpty(), fn ($query): mixed => $query)
                ->get();

            $variantsToDelete->each(
                /** @param Model&ProductVariant $variant */
                fn (ProductVariant $variant) => $variant->delete()
            );

            $product->variants()->whereKey($existingVariantIds)->update(['sku' => null]);

            $position = (int) $product->variants()->max('position');
            $unit = is_no_division_currency(shopper_currency()) ? 1 : 100;

            foreach ($variants as $key => $variantState) {
                $sku = blank($variantState['sku']) ? null : $variantState['sku'];

                if ($variantState['variant_id']) {
                    /** @var ProductVariant $variant */
                    $variant = $product->variants()->findOrFail($variantState['variant_id']);
                    $variant->update(['name' => $variantState['name'], 'sku' => $sku]);
                } else {
                    /** @var ProductVariant $variant */
                    $variant = resolve(ProductVariant::class)::query()->create([
                        'name' => $variantState['name'],
                        'product_id' => $product->id,
                        'sku' => $sku,
                        'position' => ++$position,
                    ]);

                    $stock = (int) data_get($variantState, 'stock');

                    if ($stock > 0) {
                        app()->call(InitialQuantityInventory::class, [
                            'quantity' => $stock,
                            'product' => $variant,
                        ]);
                    }
                }

                $variants[$key]['variant_id'] = $variant->id;

                $price = (int) round((float) $variantState['price'] * $unit);

                if ($price > 0) {
                    /** @var int $defaultCurrencyId */
                    $defaultCurrencyId = shopper_setting('default_currency_id');

                    $existingCompare = $variant->prices()
                        ->where('currency_id', $defaultCurrencyId)
                        ->value('compare_amount');

                    $variant->prices()->updateOrCreate(
                        ['currency_id' => $defaultCurrencyId],
                        [
                            'amount' => $price,
                            // A compare price at or below the selling price is a misleading strikethrough.
                            'compare_amount' => $existingCompare !== null && (int) $existingCompare > $price
                                ? (int) $existingCompare
                                : null,
                        ],
                    );
                }

                $variant->values()->sync($variantState['values']);
            }

            DB::commit();

            return $variants;
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
