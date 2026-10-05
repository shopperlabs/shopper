<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Pricing\PricingContext;
use Shopper\Core\Pricing\QuantityRule;

interface QuantityRuleResolver
{
    public function resolve(Priceable&Model $purchasable, PricingContext $context): ?QuantityRule;
}
