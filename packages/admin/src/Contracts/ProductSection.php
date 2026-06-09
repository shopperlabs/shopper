<?php

declare(strict_types=1);

namespace Shopper\Contracts;

use Shopper\Core\Models\Contracts\Product;

interface ProductSection extends NavigationItem
{
    public function url(Product $product): string;

    public function visible(Product $product): bool;
}
