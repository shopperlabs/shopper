<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;

enum AddressType: string implements HasLabel
{
    use ArrayableEnum;

    case Billing = 'billing';

    case Shipping = 'shipping';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Shipping => __('shopper-core::enum/address.shipping'),
            self::Billing => __('shopper-core::enum/address.billing'),
        };
    }
}
