<?php

declare(strict_types=1);

namespace Shopper\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shopper\Core\Models\Price;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @property-read \Illuminate\Support\Collection<int, Price> $prices
 */
interface Priceable
{
    /**
     * @return MorphMany<Price, TModel>
     */
    public function prices(): MorphMany;
}
