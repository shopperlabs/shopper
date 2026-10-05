<?php

declare(strict_types=1);

namespace Shopper\Core\Pricing;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Shopper\Core\Contracts\ProductPriceIndex;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Price;
use stdClass;

final class CatalogProductPriceIndex implements ProductPriceIndex
{
    public function minPriceExpression(Builder $query, int $currencyId, PricingContext $context): string
    {
        $grammar = $query->getQuery()->getGrammar();
        $productsTable = $query->getModel()->getTable();
        $productMorph = $grammar->quoteString($query->getModel()->getMorphClass());

        /** @var Model $variantModel */
        $variantModel = resolve(ProductVariant::class);
        $variantsTable = $variantModel->getTable();
        $variantMorph = $grammar->quoteString($variantModel->getMorphClass());
        $variantType = $grammar->quoteString(ProductType::Variant->value);
        $pricesTable = (new Price)->getTable();

        $own = "SELECT MIN({$pricesTable}.amount) FROM {$pricesTable}"
            ." WHERE {$pricesTable}.priceable_type = {$productMorph}"
            ." AND {$pricesTable}.priceable_id = {$productsTable}.id"
            ." AND {$pricesTable}.currency_id = {$currencyId}";

        $variant = "SELECT MIN({$pricesTable}.amount) FROM {$pricesTable}"
            ." INNER JOIN {$variantsTable} ON {$variantsTable}.id = {$pricesTable}.priceable_id"
            ." WHERE {$pricesTable}.priceable_type = {$variantMorph}"
            ." AND {$variantsTable}.product_id = {$productsTable}.id"
            ." AND {$pricesTable}.currency_id = {$currencyId}";

        return "CASE WHEN {$productsTable}.type = {$variantType} THEN ({$variant}) ELSE ({$own}) END";
    }

    public function ranges(Collection $products, int $currencyId, PricingContext $context): Collection
    {
        $ownProducts = $products->filter(
            fn (Model $product): bool => $product->getAttribute('type') !== ProductType::Variant
        );

        $variantProducts = $products->filter(
            fn (Model $product): bool => $product->getAttribute('type') === ProductType::Variant
        );

        return $this->ownPriceRanges($ownProducts, $currencyId)
            ->union($this->variantPriceRanges($variantProducts, $currencyId));
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
