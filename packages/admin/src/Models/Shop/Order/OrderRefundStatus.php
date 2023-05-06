<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\Shop\Order;

class OrderRefundStatus
{
    public const PENDING = 'pending';

    public const TREATMENT = 'treatment';

    public const PARTIAL_REFUND = 'partial_refund';

    public const REFUNDED = 'refunded';

    public const REJECTED = 'rejected';

    public const CANCELLED = 'cancelled';

    public static function values(): array
    {
        return [
            self::PENDING => __('Pending'),
            self::TREATMENT => __('Treatment'),
            self::PARTIAL_REFUND => __('Partially refunded'),
            self::REFUNDED => __('Refunded'),
            self::REJECTED => __('Rejected'),
            self::CANCELLED => __('Cancelled'),
        ];
    }
}
