<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Models\Product;

final class ProductObserver
{
    public function deleting(Product $product): void
    {
        $product->media()->delete();
        $product->prices()->delete();
        $product->clearStock();
    }
}
