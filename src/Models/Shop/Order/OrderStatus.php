<?php

namespace Shopper\Framework\Models\Shop\Order;

class OrderStatus
{
    public const PENDING = 'pending';

    public const REGISTER = 'register';

    public const PAID = 'paid';

    public const COMPLETED = 'completed';

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
