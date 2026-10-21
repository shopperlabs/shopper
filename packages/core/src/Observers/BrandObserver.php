<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\Contracts\Brand;
use Shopper\Core\Models\Contracts\Product;

class BrandObserver
{
    public function saved(Brand $brand): void
    {
        if ($brand->wasChanged(['name', 'is_enabled'])) {
            $this->reindexProducts(resolve(Product::class)::query()->where('brand_id', $brand->getKey()));
        }
    }

    public function deleting(Brand $brand): void
    {
        $brand->load('products:id,brand_id');
    }

    public function deleted(Brand $brand): void
    {
        $this->reindexProducts(resolve(Product::class)::query()->whereKey($brand->products->modelKeys()));
    }

    /**
     * @param  Builder<covariant Model>  $products
     */
    private function reindexProducts(Builder $products): void
    {
        DB::afterCommit(fn () => resolve(Product::class)->indexer()->makeAllSearchableUsing($products)->searchable());
    }
}
