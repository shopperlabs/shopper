<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shopper\Core\Models\Price;
use Shopper\Core\Pricing\PricingContext;
use Shopper\Core\Pricing\ResolvedPrice;

/**
 * @template TModel of Model
 *
 * @property-read Collection<int, Price> $prices
 */
interface Priceable
{
    public function resolvePrice(?PricingContext $context = null): ?ResolvedPrice;

    public function getPrice(?string $currencyCode = null): ?Price;

    /**
     * @return MorphMany<Price, TModel>
     */
    public function prices(): MorphMany;
}
