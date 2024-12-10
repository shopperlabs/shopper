<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\Product;

final class DetachAttributesToProductAction
{
    public function __invoke(AttributeProduct $attributeProduct, Product $product): void
    {
        DB::transaction(function () use ($attributeProduct): void {
            // @Todo: Check if product has variants
            // If product has variants check if variant is attach to the value of this attribute
            // and detach variant to attribute product value

            $attributeProduct->delete();
        });
    }
}
