<?php

declare(strict_types=1);

namespace Shopper\Cart\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Shopper\Cart\Models\Cart;

final readonly class CouponRemoved
{
    use Dispatchable;

    public function __construct(
        public Cart $cart,
    ) {}
}
