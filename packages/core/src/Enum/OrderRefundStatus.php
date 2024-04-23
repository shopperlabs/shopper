<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;

enum OrderRefundStatus: string implements HasLabel
{
    use ArrayableEnum;

    case Awaiting = 'awaiting';

    case Pending = 'pending';

    case Treatment = 'treatment';

    case Partial_Refund = 'partial_refund';

    case Refunded = 'refunded';

    case Rejected = 'rejected';

    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Awaiting => __('shopper-core::status.awaiting'),
            self::Pending => __('shopper-core::status.pending'),
            self::Treatment => __('shopper-core::status.treatment'),
            self::Partial_Refund => __('shopper-core::status.partial-refund'),
            self::Refunded => __('shopper-core::status.refunded'),
            self::Rejected => __('shopper-core::status.rejected'),
            self::Cancelled => __('shopper-core::status.cancelled'),
        };
    }
}
