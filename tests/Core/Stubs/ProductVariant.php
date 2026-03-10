<?php

declare(strict_types=1);

namespace Tests\Core\Stubs;

use Shopper\Models\ProductVariant as BaseProductVariant;

final class ProductVariant extends BaseProductVariant
{
    public function customMethod(): string
    {
        return 'custom-method-called';
    }
}
