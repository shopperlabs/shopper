<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;

enum DiscountApplyTo: string implements HasLabel
{
    use ArrayableEnum;

    case Order = 'order';

    case Products = 'products';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Order => __('shopper-core::enum/discount.apply.entire_order'),
            self::Products => __('shopper-core::enum/discount.apply.specific_products'),
        };
    }
}
