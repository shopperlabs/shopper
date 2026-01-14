<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Models\Contracts\ProductVariant;

class ProductVariantObserver
{
    public function deleting(ProductVariant $variant): void
    {
        $variant->media()->delete();
        $variant->prices()->delete();
        $variant->clearStock();
    }
}
