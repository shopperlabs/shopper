<?php

declare(strict_types=1);

namespace Shopper\Navigation\Product;

use Shopper\Contracts\ProductSection;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Navigation\AbstractNavigationItem;

abstract class AbstractProductSection extends AbstractNavigationItem implements ProductSection
{
    public function visible(Product $product): bool
    {
        return true;
    }
}
