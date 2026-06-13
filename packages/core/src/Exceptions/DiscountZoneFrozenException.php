<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use RuntimeException;

final class DiscountZoneFrozenException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(__('shopper-core::exceptions.discount_zone_frozen'));
    }
}
