<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Closure;
use Shopper\Cart\Discounts\DiscountCalculator;

final readonly class ApplyDiscounts
{
    public function __construct(
        private DiscountCalculator $calculator,
    ) {}

    public function handle(CartPipelineContext $context, Closure $next): mixed
    {
        if ($context->cart->coupon_code) {
            $this->calculator->apply($context);
        }

        return $next($context);
    }
}
