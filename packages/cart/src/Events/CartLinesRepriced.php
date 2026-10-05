<?php

declare(strict_types=1);

namespace Shopper\Cart\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Collection;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Models\Contracts\Cart;

final readonly class CartLinesRepriced
{
    use Dispatchable;

    /**
     * @param  Collection<int, array{line: CartLine, from: int, to: int}>  $changes
     */
    public function __construct(
        public Cart $cart,
        public Collection $changes,
    ) {}
}
