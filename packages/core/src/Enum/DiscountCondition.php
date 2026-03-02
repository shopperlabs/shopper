<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string ApplyTo()
 * @method static string Eligibility()
 */
enum DiscountCondition: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case ApplyTo = 'apply_to';

    case Eligibility = 'eligibility';

    public function getLabel(): string
    {
        return match ($this) {
            self::ApplyTo => __('shopper-core::enum/discount.condition.apply_to'),
            self::Eligibility => __('shopper-core::enum/discount.condition.eligibility'),
        };
    }
}
