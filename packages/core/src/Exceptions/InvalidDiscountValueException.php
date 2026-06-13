<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use Exception;

final class InvalidDiscountValueException extends Exception
{
    public static function notPositive(int $value): self
    {
        return new self(__('shopper-core::exceptions.discount_value.not_positive', ['value' => $value]));
    }

    public static function percentageOutOfRange(int $value): self
    {
        return new self(__('shopper-core::exceptions.discount_value.percentage_out_of_range', ['value' => $value]));
    }
}
