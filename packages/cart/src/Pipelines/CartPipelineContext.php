<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Shopper\Cart\Models\Cart;

final class CartPipelineContext
{
    public int|float $subtotal = 0;

    public int|float $discountTotal = 0;

    public int|float $taxTotal = 0;

    public int|float $total = 0;

    public bool $taxInclusive = false;

    /** @var array<int, int> */
    public array $lineSubtotals = [];

    public function __construct(
        public readonly Cart $cart,
    ) {}
}
