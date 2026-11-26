<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Shopper\Core\Models\AttributeProduct;

final class DetachAttributesToProductAction
{
    public function __invoke(AttributeProduct $attributeProduct): void
    {
        $attributeProduct->delete();
    }
}
