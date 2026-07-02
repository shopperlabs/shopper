<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use RuntimeException;
use Shopper\Core\Enum\ShipmentStatus;

final class InvalidShipmentStatusTransitionException extends RuntimeException
{
    public static function between(?ShipmentStatus $from, ShipmentStatus $to): self
    {
        return new self(sprintf(
            'Cannot transition a shipment from "%s" to "%s".',
            $from instanceof ShipmentStatus ? $from->value : 'none',
            $to->value,
        ));
    }
}
