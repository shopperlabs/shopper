<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Exceptions\InvalidDiscountValueException;
use Shopper\Core\Models\Discount;

class DiscountObserver
{
    public function saving(Discount $discount): void
    {
        if ($discount->value <= 0) {
            throw InvalidDiscountValueException::notPositive($discount->value);
        }

        if ($discount->type === DiscountType::Percentage && $discount->value > 100) {
            throw InvalidDiscountValueException::percentageOutOfRange($discount->value);
        }
    }
}
