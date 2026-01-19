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

            foreach ($variants as $key => $variantState) {
                /** @var ProductVariant $variant */
                $variant = $variantState['variant_id']
                    ? resolve(ProductVariant::class)::query()->findOrFail($variantState['variant_id'])
                    : resolve(ProductVariant::class)::query()->create([
                        'name' => $variantState['name'],
                        'product_id' => $product->id,
                        'sku' => $variantState['sku'],
                    ]);

                $variants[$key]['variant_id'] = $variant->id;

                $price = (float) $variantState['price'];

                if ($price > 0) {
                    /** @var int $defaultCurrencyId */
                    $defaultCurrencyId = shopper_setting('default_currency_id');

                    $variant->prices()
                        ->where('currency_id', $defaultCurrencyId)
                        ->delete();

                    $variant->prices()->create([
                        'amount' => $price,
                        'currency_id' => $defaultCurrencyId,
                    ]);
                }

                /** @var int $stock */
                $stock = data_get($variantState, 'stock');

                if ($stock > 0) {
                    $variant->clearStock();

                    app()->call(InitialQuantityInventory::class, [
                        'quantity' => $stock,
                        'product' => $variant,
                    ]);
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
