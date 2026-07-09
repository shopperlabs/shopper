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
        $values = Arr::except($data, ['quantity', 'prices', 'values']);

        DB::beginTransaction();

        try {
            $values['position'] = (int) resolve(ProductVariant::class)::query()
                ->where('product_id', $values['product_id'])
                ->max('position') + 1;

            $variant = resolve(ProductVariant::class)::query()->create($values);

            if ($pricing = data_get($data, 'prices')) {
                app()->call(SavePricingAction::class, [
                    'model' => $variant,
                    'pricing' => $pricing,
                ]);
            }

            if ($values = data_get($data, 'values')) {
                $variant->values()->sync(array_values($values));
            }

            /** @var int $quantity */
            $quantity = data_get($data, 'quantity');

            if ($quantity > 0) {
                app()->call(InitialQuantityInventory::class, [
                    'quantity' => $quantity,
                    'product' => $variant,
                ]);
            }

            DB::commit();

            return $variant;
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
