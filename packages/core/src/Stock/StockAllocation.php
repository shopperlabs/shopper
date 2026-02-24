<?php

declare(strict_types=1);

namespace Shopper\Core\Stock;

final readonly class StockAllocation
{
    public function __construct(
        public int $inventoryId,
        public int $quantity,
    ) {}
}
