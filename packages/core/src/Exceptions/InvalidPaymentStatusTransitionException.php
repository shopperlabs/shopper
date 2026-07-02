<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use RuntimeException;
use Shopper\Core\Enum\PaymentStatus;

final class InvalidPaymentStatusTransitionException extends RuntimeException
{
    public static function between(PaymentStatus $from, PaymentStatus $to): self
    {
        return new self(sprintf(
            'Cannot transition a payment from "%s" to "%s".',
            $from->value,
            $to->value,
        ));
    }
}
