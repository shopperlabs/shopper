<?php

declare(strict_types=1);

namespace Shopper\Core\Pricing;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Contracts\QuantityRuleResolver;

final class DefaultQuantityRuleResolver implements QuantityRuleResolver
{
    public function resolve(Priceable&Model $purchasable, PricingContext $context): ?QuantityRule
    {
        return null;
    }
}
