<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Illuminate\Support\Facades\DB;
use Laravel\Scout\ModelObserver;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;

class ProductVariantObserver
{
    public function created(ProductVariant $variant): void
    {
        $this->reindexProduct($variant);
    }

    public function updated(ProductVariant $variant): void
    {
        if ($variant->wasChanged('sku')) {
            $this->reindexProduct($variant);
        }
    }

    public function deleting(ProductVariant $variant): void
    {
        $variant->media()->delete();
        $variant->prices()->delete();
        $variant->clearStock();
    }

    public function deleted(ProductVariant $variant): void
    {
        $this->reindexProduct($variant);
    }

    private function reindexProduct(ProductVariant $variant): void
    {
        $product = resolve(Product::class);

        if (ModelObserver::syncingDisabledFor($product)) {
            return;
        }

        DB::afterCommit(fn () => $product::query()->whereKey($variant->product_id)->searchable());
    }
}
