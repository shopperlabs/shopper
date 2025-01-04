<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Support\Facades\DB;
use Shopper\Actions\Store\InitialQuantityInventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Repositories\VariantRepository;

final class SaveProductVariantsAction
{
    /**
     * @param  array<string, mixed>  $variants
     */
    public function __invoke(array $variants, Product $product): array
    {
        DB::beginTransaction();

        foreach ($variants as $variantState) {
            /** @var ProductVariant $variant */
            $variant = $variantState['variant_id']
            ? (new VariantRepository)->getById($variantState['variant_id'])
            : (new VariantRepository)->create([
                'name' => $variantState['name'],
                'product_id' => $product->id,
                'sku' => $variantState['sku'],
            ]);

            $price = (float) $variantState['price'];

            if ($price > 0) {
                $defaultCurrencyId = (int) shopper_setting('default_currency_id');

                $variant->prices()
                    ->where('currency_id', $defaultCurrencyId)
                    ->delete();

                $variant->prices()->create([
                    'amount' => $price,
                    'currency_id' => $defaultCurrencyId,
                ]);
            }

            $stock = (int) data_get($variantState, 'stock');

            if ($stock > 0) {
                $variant->clearStock();

                app()->call(InitialQuantityInventory::class, [
                    'quantity' => $stock,
                    'product' => $variant,
                ]);
            }

            $variant->values()->sync($variantState['values']);
        }

        $variantIds = collect($variants)->pluck('variant_id');

        $product->variants()->whereNotIn('id', $variantIds)
            ->get()
            ->each(
                fn ($variant) => $variant->delete()
            );

        DB::commit();

        return $variants;
    }
}
