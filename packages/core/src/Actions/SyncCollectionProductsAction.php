<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Shopper\Core\Models\Contracts\Collection;
use Shopper\Core\Queries\CollectionProductsQuery;

final class SyncCollectionProductsAction
{
    public function execute(Collection $collection): int
    {
        if ($collection->isManual()) {
            return 0;
        }

        $productIds = (new CollectionProductsQuery)
            ->query($collection)
            ->pluck('id')
            ->toArray();

        $collection->products()->sync($productIds);

        return count($productIds);
    }
}
