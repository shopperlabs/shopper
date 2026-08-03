<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Price;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilderRequest;

trait HandlesPriceQueries
{
    /**
     * Whether the request needs the price aggregate on the query itself
     * (sorting or bounding by price). The aggregate subquery is only added
     * then, so plain listings pay nothing for it. Read through the spatie
     * request so renamed query parameters keep being detected.
     */
    protected function wantsPriceQuery(): bool
    {
        return $this->wantsPriceSort() || $this->wantsPriceBounds();
    }

    protected function wantsPriceSort(): bool
    {
        $sorts = QueryBuilderRequest::fromRequest(request())->sorts();

        return $sorts->contains('price') || $sorts->contains('-price');
    }

    protected function wantsPriceBounds(): bool
    {
        $filters = QueryBuilderRequest::fromRequest(request())->filters();

        return $filters->has('price_min') || $filters->has('price_max');
    }

    protected function wantsPriceRange(): bool
    {
        return QueryBuilderRequest::fromRequest(request())->includes()->contains('price_range');
    }

    /**
     * @param  Builder<Model>  $query
     * @return array{min: int, max: int}|null
     */
    protected function priceRange(string $resource, Builder $query, int $currencyId): ?array
    {
        $sql = $this->minPriceExpression($query, $currencyId);

        $row = $this->apiQuery($resource, $query, [
            AllowedFilter::callback('currency', static function (): void {}),
            AllowedFilter::callback('price_min', static function (): void {}),
            AllowedFilter::callback('price_max', static function (): void {}),
        ])
            ->getEloquentBuilder()
            ->reorder()
            ->toBase()
            ->select([])
            ->selectRaw("MIN({$sql}) as min_amount, MAX({$sql}) as max_amount")
            ->first();

        if ($row === null || $row->min_amount === null) {
            return null;
        }

        return ['min' => (int) $row->min_amount, 'max' => (int) $row->max_amount];
    }

    /**
     * Add the `min_price` aggregate to the select, branched by product type
     * exactly like stock is: a variant product aggregates its variants'
     * prices, every other type its own rows (a null type counts as own).
     * Product-level rows left behind by a conversion to the variant type can
     * never surface a price nobody can be charged. Products without a price
     * in the resolved currency are excluded: they are not purchasable in
     * that currency, and excluding them keeps the ordering identical across
     * MySQL, Postgres and SQLite. When a price bound is requested the
     * explicit exclusion is skipped: a NULL aggregate never satisfies the
     * bound comparison, so re-stating it would only duplicate the EXISTS
     * probes on both the count and select queries.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected function applyPriceAggregate(Builder $query, int $currencyId): Builder
    {
        $sql = $this->minPriceExpression($query, $currencyId);

        $query
            ->select($query->qualifyColumn('*'))
            ->selectRaw("({$sql}) as min_price");

        if ($this->wantsPriceBounds()) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($currencyId): void {
            $query
                ->where(function (Builder $query) use ($currencyId): void {
                    $query
                        ->where(function (Builder $query): void {
                            $query
                                ->where('type', '<>', ProductType::Variant)
                                ->orWhereNull('type');
                        })
                        ->whereHas('prices', fn (Builder $query) => $query
                            ->where('currency_id', $currencyId)
                            ->whereNotNull('amount'));
                })
                ->orWhere(function (Builder $query) use ($currencyId): void {
                    $query
                        ->where('type', ProductType::Variant)
                        ->whereHas('variants.prices', fn (Builder $query) => $query
                            ->where('currency_id', $currencyId)
                            ->whereNotNull('amount'));
                });
        });
    }

    /**
     * A `filter[price_min]` / `filter[price_max]` bound on the same aggregate
     * the sort uses. Amounts are minor units in the resolved currency. A NULL
     * aggregate never satisfies the comparison, so unpriced products are
     * excluded by the bound itself.
     */
    protected function priceBoundFilter(string $bound, int $currencyId): AllowedFilter
    {
        return AllowedFilter::callback('price_'.$bound, function (Builder $query, mixed $value) use ($bound, $currencyId): void {
            $sql = $this->minPriceExpression($query, $currencyId);

            $query->whereRaw("({$sql}) ".($bound === 'min' ? '>=' : '<=').' ?', [(int) $value]);
        });
    }

    /**
     * The filter names the price handling claims on the allowlist without
     * touching the query: `currency` is consumed by the currency resolution.
     *
     * @return array<int, AllowedFilter>
     */
    protected function priceFilters(int $currencyId): array
    {
        return [
            AllowedFilter::callback('currency', static function (): void {}),
            $this->priceBoundFilter('min', $currencyId),
            $this->priceBoundFilter('max', $currencyId),
        ];
    }

    /**
     * The aggregate as a self-contained SQL expression with every value
     * inlined (server-controlled morph aliases, enum value, currency id;
     * never user input). Bindings are deliberately avoided: cursor
     * pagination copies the sorted expression into its WHERE clause without
     * carrying select bindings along, which would desalign every
     * placeholder after it.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    private function minPriceExpression(Builder $query, int $currencyId): string
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
}
