<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use RuntimeException;
use Shopper\Core\Enum\OrderStatus;

final class InvalidOrderStatusTransitionException extends RuntimeException
{
    public static function between(OrderStatus $from, OrderStatus $to): self
    {
        return new self(sprintf(
            'Cannot transition an order from "%s" to "%s".',
            $from->value,
            $to->value,
        ));
    }
}
