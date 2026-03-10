<?php

declare(strict_types=1);

namespace Tests\Core\Stubs;

use Shopper\Models\Product as BaseProduct;

final class Product extends BaseProduct
{
    public function customMethod(): string
    {
        return 'custom-method-called';
    }
}
