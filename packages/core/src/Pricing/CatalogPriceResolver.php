<?php

declare(strict_types=1);

namespace Shopper\Core\Pricing;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Contracts\PriceResolver;
use Shopper\Core\Models\Price;

final class CatalogPriceResolver implements PriceResolver
{
    public function resolve(Priceable&Model $priceable, PricingContext $context): ?ResolvedPrice
    {
        $price = $priceable->getPrice($context->currency());

        if (! $price instanceof Price) {
            return null;
        }

        return new ResolvedPrice(
            amount: (int) $price->amount,
            currencyCode: $price->currency_code,
            compareAmount: $price->compare_amount,
        );
    }
}
