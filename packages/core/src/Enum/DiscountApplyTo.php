<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Traits\ArrayableEnum;

enum DiscountApplyTo: string
{
    use ArrayableEnum;

    case ORDER = 'order';

    case PRODUCTS = 'products';

    public function label(): string
    {
        return match ($this) {
            self::ORDER => __('shopper::pages/discounts.entire_order'),
            self::PRODUCTS => __('shopper::pages/discounts.specific_products'),
        };
    }
}
