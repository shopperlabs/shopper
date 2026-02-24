<?php

declare(strict_types=1);

namespace Shopper\Core\Stock;

use Illuminate\Database\Eloquent\Collection;
use Shopper\Core\Contracts\InventoryResolver;
use Shopper\Core\Models\Contracts\Stockable;
use Shopper\Core\Models\Inventory;

final class DefaultInventoryResolver implements InventoryResolver
{
    /**
     * @return Collection<int, Inventory>
     */
    public function resolve(Stockable $product): Collection
    {
        return Inventory::query()
            ->orderBy('priority')
            ->orderBy('id')
            ->get();
    }
}
