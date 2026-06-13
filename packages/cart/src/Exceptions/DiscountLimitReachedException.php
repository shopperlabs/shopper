<?php

declare(strict_types=1);

namespace Shopper\Cart\Exceptions;

use RuntimeException;

final class DiscountLimitReachedException extends RuntimeException
{
    public static function global(string $code): self
    {
        return new self(__('shopper-cart::exceptions.discount_limit.global', ['code' => $code]));
    }

    public static function perUser(string $code): self
    {
        return new self(__('shopper-cart::exceptions.discount_limit.per_user', ['code' => $code]));
    }
}
