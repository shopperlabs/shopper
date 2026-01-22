<?php

declare(strict_types=1);

namespace Shopper\Shipping\Exceptions;

use Exception;

final class ShippingException extends Exception
{
    public static function notConfigured(string $driver): self
    {
        return new self("The [{$driver}] shipping driver is not configured. Please check your .env file.");
    }

    public static function notSupported(string $method, string $driver): self
    {
        return new self("The [{$method}] method is not supported by the [{$driver}] driver.");
    }

    public static function apiError(string $driver, string $message): self
    {
        return new self("API error from [{$driver}]: {$message}");
    }

    public static function invalidResponse(string $driver): self
    {
        return new self("Invalid response received from [{$driver}] API.");
    }
}
