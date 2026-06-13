<?php

declare(strict_types=1);

namespace Shopper\Cart\Exceptions;

use RuntimeException;

final class CartCompletedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(__('shopper-cart::exceptions.cart_completed'));
    }
}
