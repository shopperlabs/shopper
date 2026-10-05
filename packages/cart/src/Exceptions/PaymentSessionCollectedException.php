<?php

declare(strict_types=1);

namespace Shopper\Cart\Exceptions;

use RuntimeException;

final class PaymentSessionCollectedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(__('shopper-cart::exceptions.payment_session_collected'));
    }
}
