<?php

declare(strict_types=1);

namespace Shopper\Cart\Exceptions;

use RuntimeException;

final class CartNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(__('shopper-cart::messages.exceptions.cart_not_found'));
    }
}
