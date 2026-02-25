<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Closure;

final class Calculate
{
    public function handle(CartPipelineContext $context, Closure $next): mixed
    {
        $context->total = max(0, $context->taxInclusive
            ? $context->subtotal - $context->discountTotal
            : $context->subtotal - $context->discountTotal + $context->taxTotal);

        return $next($context);
    }
}
