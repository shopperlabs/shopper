<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Closure;
use Shopper\Cart\Discounts\PromotionResolver;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;

final readonly class ApplyDiscounts
{
    public function __construct(
        private PromotionResolver $resolver,
    ) {}

    public function handle(CartPipelineContext $context, Closure $next): mixed
    {
        $cart = $context->cart;

        if ($cart->promotions->isNotEmpty() || $this->holdsStaleAdjustments($cart)) {
            $this->resolver->resolve($context);
        }

        return $next($context);
    }

    private function holdsStaleAdjustments(Cart $cart): bool
    {
        return ! $cart->holdsProviderPaymentSession()
            && $cart->lines->contains(fn (CartLine $line): bool => $line->adjustments->isNotEmpty());
    }
}
