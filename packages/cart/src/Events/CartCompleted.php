<?php

declare(strict_types=1);

namespace Shopper\Cart\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Shopper\Core\Models\Contracts\Cart;
use Shopper\Core\Models\Contracts\Order;

final readonly class CartCompleted
{
    use Dispatchable;

    public function __construct(
        public Cart $cart,
        public Order $order,
    ) {}
}
