<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Shopper\Core\Pricing\PricingContext;
use stdClass;

interface ProductPriceIndex
{
    /**
     * Raw SQL without bindings: inline server-controlled values only.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    public function minPriceExpression(Builder $query, int $currencyId, PricingContext $context): string;

    /**
     * @param  Collection<int, Model>  $products
     * @return Collection<int|string, stdClass>
     */
    public function ranges(Collection $products, int $currencyId, PricingContext $context): Collection;
}
