<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Shopper\Actions\Store\InitialQuantityInventory;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Repositories\VariantRepository;

final class CreateNewVariant
{
    public function __invoke(array $state): ProductVariant
    {
        $data = Arr::except($state, ['quantity', 'prices', 'values']);

        DB::beginTransaction();

        /** @var ProductVariant $variant */
        $variant = (new VariantRepository)->create($data);

        if ($pricing = data_get($state, 'prices')) {
            app()->call(SavePricingAction::class, [
                'model' => $variant,
                'pricing' => $pricing,
            ]);
        }

        if ($values = data_get($state, 'values')) {
            $variant->values()->sync($values);
        }

        $quantity = data_get($state, 'quantity');

        if ($quantity && (int) $quantity > 0) {
            app()->call(InitialQuantityInventory::class, [
                'quantity' => $quantity,
                'product' => $variant,
            ]);
        }

        DB::commit();

        return $variant;
    }
}
