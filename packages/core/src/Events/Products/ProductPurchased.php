<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Products;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\Contracts\Product;

final class ProductPurchased implements ShouldQueueAfterCommit
{
    use Dispatchable, InteractsWithQueue, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Product $product,
        public readonly int $quantity,
        public readonly ?int $inventoryId = null
    ) {}
}
