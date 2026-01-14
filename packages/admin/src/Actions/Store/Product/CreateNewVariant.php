<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Shopper\Actions\Store\InitialQuantityInventory;
use Shopper\Core\Models\Contracts\ProductVariant;
use Throwable;

final class CreateNewVariant
{
    /**
     * @param  array<string, mixed>  $data
     *
     * @throws Throwable
     */
    public function __invoke(array $data): ProductVariant
    {
        $data = Arr::except($data, ['quantity', 'prices', 'values']);

        DB::beginTransaction();

        $variant = resolve(ProductVariant::class)::query()->create($data);

        if ($pricing = data_get($data, 'prices')) {
            app()->call(SavePricingAction::class, [
                'model' => $variant,
                'pricing' => $pricing,
            ]);
        }

        if ($values = data_get($data, 'values')) {
            $variant->values()->sync($values);
        }

        $quantity = data_get($data, 'quantity');

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
