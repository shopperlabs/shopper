<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string None()
 * @method static string Price()
 * @method static string Quantity()
 */
enum DiscountRequirement: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

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
