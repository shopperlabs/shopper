<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Jobs\SyncProductWithCollectionsJob;
use Shopper\Core\Models\Contracts\Product;

class ProductObserver
{
    public function saved(Product $product): void
    {
        SyncProductWithCollectionsJob::dispatch($product)->afterCommit();
    }

    public function deleting(Product $product): void
    {
        $product->media()->delete();
        $product->prices()->delete();
        $product->clearStock();
    }
}
