<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Shopper\Actions\Store\InitialQuantityInventory;
use Shopper\Core\Models\ProductVariant;
use Throwable;

final class CreateNewVariant
{
    /**
     * @param  array<string, mixed>  $state
     *
     * @throws Throwable
     */
    public function __invoke(array $state): ProductVariant
    {
        $data = Arr::except($state, ['quantity', 'prices', 'values']);

        DB::beginTransaction();

        $variant = ProductVariant::resolvedQuery()->create($data);

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
