<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Pricing\PricingContext;

interface PreloadsPrices
{
    /**
     * @param  iterable<Priceable&Model>  $priceables
     */
    public function preload(iterable $priceables, PricingContext $context): void;
}
