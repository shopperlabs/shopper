<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shopper\Core\Models\Price;

/**
 * @template TModel of Model
 *
 * @property-read Collection<int, Price> $prices
 */
interface Priceable
{
    public function getPrice(?string $currencyCode = null): ?Price;

    /**
     * @return MorphMany<Price, TModel>
     */
    public function prices(): MorphMany;
}
