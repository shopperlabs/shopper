<?php

declare(strict_types=1);

namespace Shopper\Cart\Exceptions;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

final class InsufficientStockException extends RuntimeException
{
    public function __construct(
        public readonly Model $purchasable,
        public readonly int $available,
        public readonly int $requested,
    ) {
        parent::__construct(__('shopper-cart::messages.exceptions.insufficient_stock'));
    }
}
