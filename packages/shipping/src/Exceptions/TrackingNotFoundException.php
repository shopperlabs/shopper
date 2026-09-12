<?php

declare(strict_types=1);

namespace Shopper\Shipping\Exceptions;

final class TrackingNotFoundException extends ShippingException
{
    public static function for(string $driver, string $trackingNumber): self
    {
        return new self("The [{$driver}] carrier has no record of the tracking number [{$trackingNumber}].");
    }
}
