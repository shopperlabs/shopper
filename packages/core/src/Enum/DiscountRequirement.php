<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Traits\ArrayableEnum;

enum DiscountRequirement: string
{
    use ArrayableEnum;

    case NONE = 'none';

    case PRICE = 'price';

    case QUANTITY = 'quantity';

    public function label(): string
    {
        return match ($this) {
            self::NONE => __('shopper::pages/discounts.none'),
            self::PRICE => __('shopper::pages/discounts.min_amount', ['currency' => shopper_currency()]),
            self::QUANTITY => __('shopper::pages/discounts.min_quantity'),
        };
    }
}
