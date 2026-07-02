<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Illuminate\Pipeline\Pipeline;
use Shopper\Cart\Models\Cart;

final readonly class CartPipelineRunner
{
    public function __construct(
        private CartPipeline $pipeline,
    ) {}

    public function run(Cart $cart): CartPipelineContext
    {
        $cart->loadMissing([
            'lines.purchasable',
            'lines.adjustments',
            'lines.taxLines',
            'addresses.country',
            'promotions.discount.campaign',
            'promotions.discount.items',
        ]);

        $context = new CartPipelineContext($cart);

        /** @var list<class-string> $base */
        $base = config('shopper.cart.pipelines.cart', []);

        app(Pipeline::class)
            ->send($context)
            ->through($this->pipeline->build($base))
            ->thenReturn();

        return $context;
    }
}
