<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Traits\ArrayableEnum;

enum DiscountEligibility: string
{
    use ArrayableEnum;

    case EVERYONE = 'everyone';

    case CUSTOMERS = 'customers';

    public function label(): string
    {
        return match ($this) {
            self::EVERYONE => __('shopper::pages/discounts.everyone'),
            self::CUSTOMERS => __('shopper::pages/discounts.specific_customers'),
        };
    }
}
