<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Shopper\Core\Contracts\ProductPriceIndex;

trait LoadsPriceRange
{
    use ResolvesPricingContext;

    /**
     * Batch-load the min/max price aggregate for the products of a response
     * in the resolved currency, before serialization. Variant products
     * aggregate their variants' prices, every other type its own rows, so a
     * stale product-level row on a variant product never leaks into the
     * range. Query count is constant regardless of page size. A null
     * currency marks every product with a null range, keeping the payload
     * shape stable on shops without a resolvable currency.
     *
     * @param  Collection<int, Model>  $products
     */
    protected function loadPriceRangeForProducts(Collection $products, ?int $currencyId): void
    {
        $products = $products
            ->merge($products->flatMap(fn (Model $product): Collection => $this->loadedRelation($product, 'relatedProducts')))
            ->unique(fn (Model $product) => $product->getKey())
            ->values();

        if ($products->isEmpty()) {
            return;
        }

        if ($currencyId === null) {
            $products->each(function (Model $product): void {
                $product->setAttribute('price_range_min', null);
                $product->setAttribute('price_range_max', null);
            });

            return;
        }

        $ranges = resolve(ProductPriceIndex::class)->ranges(
            $products,
            $currencyId,
            $this->requestPricingContext(),
        );

        $products->each(function (Model $product) use ($ranges): void {
            $row = $ranges->get($product->getKey());

            $product->setAttribute('price_range_min', $row !== null ? (int) $row->min_amount : null);
            $product->setAttribute('price_range_max', $row !== null ? (int) $row->max_amount : null);
        });
    }
}
