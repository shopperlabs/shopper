<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

enum OrderRefundStatus
{
    case PENDING;
    case TREATMENT;
    case PARTIAL_REFUND;
    case REFUNDED;
    case REJECTED;
    case CANCELLED;

    public function value(): string
    {
        return match ($this) {
            self::PENDING => __('shopper::status.pending'),
            self::TREATMENT => __('Treatment'),
            self::PARTIAL_REFUND => __('Partially refunded'),
            self::REFUNDED => __('Refunded'),
            self::REJECTED => __('Rejected'),
            self::CANCELLED => __('shopper::status.cancelled'),
        };
    }
}
