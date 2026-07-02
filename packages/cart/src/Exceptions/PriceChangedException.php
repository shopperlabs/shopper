<?php

declare(strict_types=1);

namespace Shopper\Cart\Exceptions;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

final class PriceChangedException extends RuntimeException
{
    public function __construct(
        public readonly Model $purchasable,
        public readonly int $was,
        public readonly int $now,
    ) {
        parent::__construct(__('shopper-cart::exceptions.price_changed'));
    }
}
