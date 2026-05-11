<?php

declare(strict_types=1);

namespace Shopper\Cart\Exceptions;

use RuntimeException;

final class DiscountLimitReachedException extends RuntimeException
{
    public static function global(string $code): self
    {
        return new self(
            "The discount [{$code}] reached its global usage limit between cart validation and order commit. "
            .'No order was created.'
        );
    }

    public static function perUser(string $code): self
    {
        return new self(
            "The discount [{$code}] has already been redeemed by this customer and is limited to one use per customer. "
            .'No order was created.'
        );
    }
}
