<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Events\Products\ProductUpdated;
use Shopper\Core\Models\Contracts\Product;

final class UpdateProductAction
{
    /**
     * @param  array<string, mixed>  $values
     * @param  Model&Product  $product
     */
    public function __invoke(array $values, Product $product): Product
    {
        $product->update($values);
        $product->refresh();

        event(new ProductUpdated($product));

        return $product;
    }
}
