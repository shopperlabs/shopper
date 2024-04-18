<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Traits\ArrayableEnum;

enum DiscountType: string
{
    use ArrayableEnum;

    case PERCENTAGE = 'percentage';

    case FIXED_AMOUNT = 'fixed_amount';

    public function label(): string
    {
        return match ($this) {
            self::PERCENTAGE => __('shopper::pages/discounts.percentage'),
            self::FIXED_AMOUNT => __('shopper::pages/discounts.fixed_amount'),
        };
    }
}
