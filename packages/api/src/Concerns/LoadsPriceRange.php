<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Price;
use stdClass;

trait LoadsPriceRange
{
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

        $ownProducts = $products->filter(
            fn (Model $product): bool => $product->getAttribute('type') !== ProductType::Variant
        );

        $variantProducts = $products->filter(
            fn (Model $product): bool => $product->getAttribute('type') === ProductType::Variant
        );

        $ranges = $this->ownPriceRanges($ownProducts, $currencyId)
            ->union($this->variantPriceRanges($variantProducts, $currencyId));

        $products->each(function (Model $product) use ($ranges): void {
            $row = $ranges->get($product->getKey());

            $product->setAttribute('price_range_min', $row !== null ? (int) $row->min_amount : null);
            $product->setAttribute('price_range_max', $row !== null ? (int) $row->max_amount : null);
        });
    }

    /**
     * @param  Collection<int, Model>  $products
     * @return Collection<int|string, stdClass>
     */
    private function ownPriceRanges(Collection $products, int $currencyId): Collection
    {
        if ($products->isEmpty()) {
            return new Collection;
        }

        return Price::query()
            ->toBase()
            ->where('priceable_type', $products->first()->getMorphClass())
            ->whereIn('priceable_id', $products->map(fn (Model $product) => $product->getKey()))
            ->where('currency_id', $currencyId)
            ->whereNotNull('amount')
            ->groupBy('priceable_id')
            ->selectRaw('priceable_id as product_id')
            ->selectRaw('MIN(amount) as min_amount')
            ->selectRaw('MAX(amount) as max_amount')
            ->get()
            ->keyBy('product_id');
    }

    /**
     * @param  Collection<int, Model>  $products
     * @return Collection<int|string, stdClass>
     */
    private function variantPriceRanges(Collection $products, int $currencyId): Collection
    {
        if ($products->isEmpty()) {
            return new Collection;
        }

        /** @var Model $variant */
        $variant = resolve(ProductVariant::class);
        $variantsTable = $variant->getTable();
        $pricesTable = (new Price)->getTable();

        return $variant->newQuery()
            ->toBase()
            ->join($pricesTable, function (JoinClause $join) use ($pricesTable, $variantsTable, $variant): void {
                $join->on($pricesTable.'.priceable_id', '=', $variantsTable.'.id')
                    ->where($pricesTable.'.priceable_type', $variant->getMorphClass());
            })
            ->whereIn($variantsTable.'.product_id', $products->map(fn (Model $product) => $product->getKey()))
            ->where($pricesTable.'.currency_id', $currencyId)
            ->whereNotNull($pricesTable.'.amount')
            ->groupBy($variantsTable.'.product_id')
            ->selectRaw($variantsTable.'.product_id as product_id')
            ->selectRaw('MIN('.$pricesTable.'.amount) as min_amount')
            ->selectRaw('MAX('.$pricesTable.'.amount) as max_amount')
            ->get()
            ->keyBy('product_id');
    }
}
