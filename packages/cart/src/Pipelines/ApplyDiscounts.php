<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Closure;
use Shopper\Cart\Discounts\PromotionResolver;

final readonly class ApplyDiscounts
{
    public function __construct(
        private PromotionResolver $resolver,
    ) {}

    public function handle(CartPipelineContext $context, Closure $next): mixed
    {
        if ($context->cart->promotions->isNotEmpty()) {
            $this->resolver->resolve($context);
        }

        return $next($context);
    }
}
