<?php

declare(strict_types=1);

namespace Shopper\Stripe\Exceptions;

use Shopper\Payment\Exceptions\PaymentException;
use Stripe\Exception\ApiErrorException;

final class StripeException extends PaymentException
{
    public static function fromApiError(ApiErrorException $e): self
    {
        return new self(
            message: "Stripe API error: {$e->getMessage()}",
            code: (int) $e->getCode(),
            previous: $e,
        );
    }

    public static function invalidWebhookPayload(string $message): self
    {
        return new self("Invalid Stripe webhook payload: {$message}");
    }
}
