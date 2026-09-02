<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use RuntimeException;

final class CannotCreateEmptyShipmentException extends RuntimeException
{
    public static function forOrder(int $orderId): self
    {
        return new self("No shippable items remain on order [{$orderId}].");
    }
}
