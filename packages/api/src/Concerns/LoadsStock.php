<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Product as CoreProduct;
use Shopper\Core\Models\ProductVariant as CoreProductVariant;

trait LoadsStock
{
    /**
     * Batch-load stock for the products of a response before serialization,
     * shaped by the product type: standard and virtual products carry their
     * own stock, variant products a per-product variant aggregate (correct on
     * listings that do not include variants), and external products none. The
     * per-variant stock of any loaded `variants` relation is batched too.
     * Query count is constant regardless of page size or variant count.
     *
     * @param  Collection<int, Model>  $products
     */
    protected function loadStockForProducts(Collection $products): void
    {
        $products = $products
            ->merge($products->flatMap(fn (Model $product): Collection => $this->loadedRelation($product, 'relatedProducts')))
            ->unique(fn (Model $product) => $product->getKey())
            ->values();

        $ownStockProducts = $products->filter(
            fn (Model $product): bool => ! in_array($product->getAttribute('type'), [ProductType::Variant, ProductType::External], true)
        );

        if ($ownStockProducts->isNotEmpty()) {
            CoreProduct::loadCurrentStock(EloquentCollection::make($ownStockProducts->all()));
        }

        $variants = $products->flatMap(fn (Model $product): Collection => $this->loadedRelation($product, 'variants'));

        if ($variants->isNotEmpty()) {
            CoreProductVariant::loadCurrentStock(EloquentCollection::make($variants->all()));
        }

        $variantProducts = $products->filter(
            fn (Model $product): bool => $product->getAttribute('type') === ProductType::Variant
        );

        if ($variantProducts->isNotEmpty()) {
            $this->loadVariantsAggregate($variantProducts);
        }
    }

    /**
     * Batch-load stock for the products rendered through a loaded relation
     * (e.g. categories or brands serialized with `include=products`).
     *
     * @param  Collection<int, Model>  $models
     */
    protected function loadStockThroughRelation(Collection $models, string $relation = 'products'): void
    {
        $products = $models->flatMap(fn (Model $model): Collection => $this->loadedRelation($model, $relation));

        if ($products->isNotEmpty()) {
            $this->loadStockForProducts($products);
        }
    }

    /**
     * One aggregate query: per product, the summed variant stock and whether
     * any variant allows backorders. Posed as raw attributes read back through
     * `getAttributes()` by the resources, because the product model already
     * has a `variants_stock` accessor that would shadow a plain attribute and
     * lazily iterate the variants relation.
     *
     * @param  Collection<int, Model>  $products
     */
    private function loadVariantsAggregate(Collection $products): void
    {
        /** @var Model $variant */
        $variant = resolve(ProductVariant::class);
        $variantsTable = $variant->getTable();
        $levelsTable = shopper_table('stock_levels');

        $aggregates = $variant->newQuery()
            ->toBase()
            ->leftJoin($levelsTable, function (JoinClause $join) use ($levelsTable, $variantsTable, $variant): void {
                $join->on($levelsTable.'.stockable_id', '=', $variantsTable.'.id')
                    ->where($levelsTable.'.stockable_type', $variant->getMorphClass());
            })
            ->whereIn($variantsTable.'.product_id', $products->map(fn (Model $product) => $product->getKey()))
            ->groupBy($variantsTable.'.product_id')
            ->selectRaw($variantsTable.'.product_id as product_id')
            ->selectRaw('COALESCE(SUM('.$levelsTable.'.quantity), 0) as variants_stock')
            ->selectRaw('MAX(CASE WHEN '.$variantsTable.'.allow_backorder THEN 1 ELSE 0 END) as variants_allow_backorder')
            ->get()
            ->keyBy('product_id');

        $products->each(function (Model $product) use ($aggregates): void {
            $row = $aggregates->get($product->getKey());

            $product->setAttribute('variants_real_stock', (int) ($row->variants_stock ?? 0));
            $product->setAttribute('variants_allow_backorder', (bool) ($row->variants_allow_backorder ?? false));
        });
    }

    /**
     * @return Collection<int, Model>
     */
    private function loadedRelation(Model $model, string $relation): Collection
    {
        $related = $model->relationLoaded($relation) ? $model->getRelation($relation) : null;

        return $related instanceof EloquentCollection ? $related->toBase() : new Collection;
    }
}
