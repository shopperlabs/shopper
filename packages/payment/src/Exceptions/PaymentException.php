<?php

declare(strict_types=1);

namespace Shopper\Payment\Exceptions;

use Exception;

class PaymentException extends Exception
{
    public static function notConfigured(string $driver): self
    {
        return new self("The [{$driver}] payment driver is not configured. Please check your .env file.");
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

    public static function webhookVerificationFailed(string $driver): self
    {
        return new self("Webhook signature verification failed for [{$driver}].");
    }

    public static function refundNotAllowed(string $status): self
    {
        return new self("Cannot refund an order whose payment is [{$status}].");
    }

    public static function refundExceedsRefundable(int $requested, int $refundable): self
    {
        return new self("Cannot refund {$requested}: only {$refundable} remains refundable on this order.");
    }
}
