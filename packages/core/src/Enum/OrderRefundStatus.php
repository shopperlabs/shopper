<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

enum OrderRefundStatus: string
{
    case PENDING = 'pending';

    case TREATMENT = 'treatment';

    case PARTIAL_REFUND = 'partial-refund';

    case REFUNDED = 'refunded';

    case REJECTED = 'rejected';

    case CANCELLED = 'cancelled';

    public function translateValue(): string
    {
        return match ($this) {
            self::PENDING => __('shopper::status.pending'),
            self::TREATMENT => __('shopper::status.treatment'),
            self::PARTIAL_REFUND => __('shopper::status.partial-refund'),
            self::REFUNDED => __('shopper::status.refunded'),
            self::REJECTED => __('shopper::status.rejected'),
            self::CANCELLED => __('shopper::status.cancelled'),
        };
    }
}
