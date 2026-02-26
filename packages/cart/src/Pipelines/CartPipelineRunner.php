<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Illuminate\Pipeline\Pipeline;
use Shopper\Cart\Models\Cart;

final class CartPipelineRunner
{
    public function run(Cart $cart): CartPipelineContext
    {
        $cart->loadMissing([
            'lines.purchasable',
            'lines.adjustments',
            'lines.taxLines',
            'addresses.country',
        ]);

        $context = new CartPipelineContext($cart);

        app(Pipeline::class)
            ->send($context)
            ->through(config('shopper.cart.pipelines.cart'))
            ->thenReturn();

        $context->subtotal /= 100;
        $context->discountTotal /= 100;
        $context->taxTotal /= 100;
        $context->total /= 100;

        return $context;
    }
}
