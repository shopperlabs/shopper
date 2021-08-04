<?php

namespace Shopper\Framework\Models\Shop\Order;

class OrderStatus
{
    /**
     * Pending orders are brand new orders that have not been processed yet.
     */
    public const PENDING = 'pending';

    /**
     * Orders that has been registered..
     */
    public const REGISTER = 'register';

    /**
     * Orders that has been paid..
     */
    public const PAID = 'paid';

    /**
     * Orders fulfilled completely.
     */
    public const COMPLETED = 'completed';

    /**
     * Order that has been cancelled.
     */
    public const CANCELLED = 'cancelled';

    public static function values(): array
    {
        return [
            self::PENDING => __('Pending'),
            self::REGISTER => __('Registered'),
            self::COMPLETED => __('Completed'),
            self::CANCELLED => __('Cancelled'),
            self::PAID => __('Paid'),
        ];
    }
}
