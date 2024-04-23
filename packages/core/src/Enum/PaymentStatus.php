<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;

enum PaymentStatus: string implements HasLabel
{
    use ArrayableEnum;

    case Paid = 'paid';

    case Pending = 'pending';

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('shopper-core::status.pending'),
            self::Completed => __('shopper-core::status.completed'),
            self::Cancelled => __('shopper-core::status.cancelled'),
            self::Paid => __('shopper-core::status.paid'),
        };
    }
}
