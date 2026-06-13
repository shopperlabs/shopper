<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Closure;

final class Calculate
{
    public function handle(CartPipelineContext $context, Closure $next): mixed
    {
        $context->shippingTotal = $context->cart->shipping_amount ?? 0;

        $goodsTotal = max(0, $context->taxInclusive
            ? $context->subtotal - $context->discountTotal
            : $context->subtotal - $context->discountTotal + $context->taxTotal);

        $context->total = $goodsTotal + $context->shippingTotal;

        return $next($context);
    }
}
