<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Products;

use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\Product;

final class Updated
{
    use SerializesModels;

    /**
     * @param Product $product
     */
    public function __construct($product)
    {
    }
}
