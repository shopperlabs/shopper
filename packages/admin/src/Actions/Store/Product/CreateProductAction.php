<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Support\Arr;
use Shopper\Actions\Store\InitialQuantityInventory;
use Shopper\Core\Events\Products\ProductCreated;
use Shopper\Core\Models\Contracts\Product;

final class CreateProductAction
{
    /**
     * @param  array<string, mixed>  $values
     */
    public function __invoke(array $values): Product
    {
        $product = resolve(Product::class)::query()->create(
            Arr::except($values, ['quantity'])
        );

        /** @var ?int $quantity */
        $quantity = data_get($values, 'quantity');

        if ($quantity && $quantity > 0) {
            app()->call(InitialQuantityInventory::class, [
                'quantity' => $quantity,
                'product' => $product,
            ]);
        }

        event(new ProductCreated($product));

        return $product;
    }
}
