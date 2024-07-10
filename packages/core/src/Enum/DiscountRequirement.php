<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;

enum DiscountRequirement: string implements HasLabel
{
    use ArrayableEnum;

    case None = 'none';

    case Price = 'price';

    case Quantity = 'quantity';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::None => __('shopper-core::enum/discount.requirement.none'),
            self::Price => __('shopper-core::enum/discount.requirement.min_amount', ['currency' => shopper_currency()]),
            self::Quantity => __('shopper-core::enum/discount.requirement.min_quantity'),
        };
    }
}
