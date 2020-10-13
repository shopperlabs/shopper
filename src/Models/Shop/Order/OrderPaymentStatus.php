<?php

namespace Shopper\Framework\Models\Shop\Order;

class OrderPaymentStatus
{
    /**
     * Pending order payments that have not been processed yet.
     */
    const PENDING = 'pending';

    /**
     * Order Payments that are currently being treated.
     */
    const TREATMENT = 'treatment';

    /**
     * Order Payments that has been partially paid.
     */
    const PARTIAL_PAID = 'partial_paid';

    /**
     * Order Payments that has been totally paid.
     */
    const PAID = 'paid';

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
            self::PARTIAL_PAID    => __('Partially paid'),
            self::PAID            => __('Paid'),
            self::REJECTED        => __('Rejected'),
            self::CANCELLED       => __('Cancelled'),
        ];
    }
}
