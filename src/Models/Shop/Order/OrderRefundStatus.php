<?php

namespace Shopper\Framework\Models\Shop\Order;

class OrderRefundStatus
{
    /**
     * Pending order refunds that have not been processed yet.
     */
    const PENDING = 'pending';

    /**
     * Order Refunds that are currently being treated.
     */
    const TREATMENT = 'treatment';

    /**
     * Order Payments that has been partially paid.
     */
    const PARTIAL_REFUND = 'partial_refund';

    /**
     * Order Payments that has been totally refunded.
     */
    const REFUNDED = 'refunded';

    /**
     * Order Payments that has been rejected.
     */
    const REJECTED = 'rejected';

    /**
     * Order that has been cancelled.
     */
    const CANCELLED = 'cancelled';

    public static function values()
    {
        return [
            self::PENDING         => __('Pending'),
            self::TREATMENT       => __('Treatment'),
            self::PARTIAL_REFUND  => __('Partially refunded'),
            self::REFUNDED        => __('Refunded'),
            self::REJECTED        => __('Rejected'),
            self::CANCELLED       => __('Cancelled'),
        ];
    }
}
