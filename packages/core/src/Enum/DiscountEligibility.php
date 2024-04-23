<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;

enum DiscountEligibility: string implements HasLabel
{
    use ArrayableEnum;

    case Everyone = 'everyone';

    case Customers = 'customers';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Everyone => __('shopper-core::enum/discount.eligibility.everyone'),
            self::Customers => __('shopper-core::enum/discount.eligibility.specific_customers'),
        };
    }
}
