<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Shopper\Core\Models\Contracts\Stockable;
use Shopper\Core\Stock\StockAllocation;

interface StockAllocator
{
    /**
     * Determine how to allocate stock for a product across inventories.
     *
     * @return array<int, StockAllocation>
     */
    public function allocate(Stockable $product, int $quantity): array;
}
