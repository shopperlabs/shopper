<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Shopper\Core\Models\Contracts\Stockable;
use Shopper\Core\Models\Inventory;

/**
 * @phpstan-type InventoryCollection Collection<int, Inventory>
 */
interface InventoryResolver
{
    /**
     * Resolve the inventories available for a given stockable product.
     *
     * @return Collection<int, Inventory>
     */
    public function resolve(Stockable $product): Collection;
}
