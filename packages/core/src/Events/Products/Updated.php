<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Products;

use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\Product;

class Updated
{
    use SerializesModels;

    public function __construct(public Product $product) {}
}
