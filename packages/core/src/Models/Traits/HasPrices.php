<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shopper\Core\Contracts\PriceResolver;
use Shopper\Core\Models\Price;
use Shopper\Core\Pricing\PricingContext;
use Shopper\Core\Pricing\ResolvedPrice;

trait HasPrices
{
    public function resolvePrice(?PricingContext $context = null): ?ResolvedPrice
    {
        return resolve(PriceResolver::class)->resolve($this, $context ?? new PricingContext);
    }

    public function getPrice(?string $currencyCode = null): ?Price
    {
        $currencyCode = $currencyCode ?? shopper_currency();

        if ($this->relationLoaded('prices')) {
            $this->prices->loadMissing('currency');

            return $this->prices
                ->first(fn (Price $price): bool => $price->currency->code === $currencyCode);
        }

        return $this->prices()
            ->with('currency')
            ->whereHas('currency', fn ($query) => $query->withoutGlobalScopes()->where('code', $currencyCode))
            ->first();
    }

    /**
     * @return MorphMany<Price, static>
     */
    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }
}
