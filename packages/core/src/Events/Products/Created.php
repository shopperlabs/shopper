<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Products;

use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\Product;

final class Created
{
    use SerializesModels;

    /**
     * @param Product $product
     */
    public function __construct($product)
    {
    }
}
