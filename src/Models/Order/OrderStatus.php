<?php

namespace Shopper\Framework\Models\Order;

class OrderStatus
{
    /**
     * Pending orders are brand new orders that have not been processed yet.
     */
    const PENDING = 'pending';

    /**
     * Orders fulfilled completely.
     */
    const COMPLETED = 'completed';

    /**
     * Order that has been cancelled.
     */
    const CANCELLED = 'cancelled';

    public static function values()
    {
        return [
            self::PENDING    => __('Pending'),
            self::COMPLETED  => __('Completed'),
            self::CANCELLED  => __('Cancelled')
        ];
    }
}
