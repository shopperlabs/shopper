<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shopper\Core\Models\Price;

trait HasPrices
{
    /**
     * @return MorphMany<Price, $this>
     */
    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }
}
