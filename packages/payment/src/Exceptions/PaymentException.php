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

    public static function orphaned(string $driver, string $reference): self
    {
        return new self("The [{$driver}] payment [{$reference}] was collected but no order was placed for it.");
    }

    public static function orphanRefundFailed(string $driver, string $reference): self
    {
        return new self("The [{$driver}] provider failed to refund the payment [{$reference}], which no order was placed for.");
    }

    public static function invalidResponse(string $driver): self
    {
        return new self("Invalid response received from [{$driver}] API.");
    }

    public static function webhookVerificationFailed(string $driver): self
    {
        return new self("Webhook signature verification failed for [{$driver}].");
    }

    public static function captureNotAllowed(string $status): self
    {
        return new self("Cannot capture the payment of an order whose status is [{$status}].");
    }

    public static function refundNotAllowed(string $status): self
    {
        return new self("Cannot refund an order whose payment is [{$status}].");
    }

    public static function refundExceedsRefundable(int $requested, int $refundable): self
    {
        return new self("Cannot refund {$requested}: only {$refundable} remains refundable on this order.");
    }

    public static function unidentifiedRefund(string $driver): self
    {
        return new self("The [{$driver}] driver reported a failed refund without a refund id or an idempotency key.");
    }

    public static function idempotencyKeyReused(string $idempotencyKey): self
    {
        return new self("The idempotency key [{$idempotencyKey}] already names a refund of another amount on this order.");
    }

    public static function duplicateRefund(string $driver, string $idempotencyKey): self
    {
        return new self("The [{$driver}] provider issued a second refund for the idempotency key [{$idempotencyKey}].");
    }
}
