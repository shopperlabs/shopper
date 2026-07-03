<?php

declare(strict_types=1);

namespace Shopper\Cart\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Shopper\Cart\Models\Contracts\Cart;
use Shopper\Core\Models\Contracts\Order;

final readonly class CartCompleted implements ShouldDispatchAfterCommit
{
    use Dispatchable;

    public function __construct(
        public Cart $cart,
        public Order $order,
    ) {}
}
