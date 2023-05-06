<?php

declare(strict_types=1);

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
            self::PENDING => __('shopper::status.pending'),
            self::REGISTER => __('shopper::status.registered'),
            self::COMPLETED => __('shopper::status.completed'),
            self::CANCELLED => __('shopper::status.cancelled'),
            self::PAID => __('shopper::status.paid'),
        ];
    }
}
