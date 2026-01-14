<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Products;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\Contracts\Product;

final class ProductCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Product $product
    ) {}
}
