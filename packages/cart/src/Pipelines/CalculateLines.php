<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Closure;

final class CalculateLines
{
    public function handle(CartPipelineContext $context, Closure $next): mixed
    {
        foreach ($context->cart->lines as $line) {
            $subtotal = $line->unit_price_amount * $line->quantity;
            $context->lineSubtotals[$line->id] = $subtotal;
            $context->subtotal += $subtotal;
        }

        return $next($context);
    }
}
