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

        return app(Pipeline::class)
            ->send($context)
            ->through(config('shopper.cart.pipelines.cart'))
            ->thenReturn();
    }
}
