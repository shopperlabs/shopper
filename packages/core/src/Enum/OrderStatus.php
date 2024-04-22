<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Traits\ArrayableEnum;

enum OrderStatus: string
{
    use ArrayableEnum;

    case New = 'new';

    case Shipped = 'shipped';

    case Delivered = 'delivered';

    case Pending = 'pending';

    case Register = 'registered';

    case Paid = 'paid';

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    public function badge(): string
    {
        return match ($this) {
            self::Pending => 'bg-yellow-100 text-yellow-800',
            self::Register => 'bg-primary-100 text-primary-800',
            self::Paid => 'bg-teal-100 text-teal-800',
            self::Cancelled => 'bg-pink-100 text-pink-800',
            self::Completed => 'bg-success-100 text-success-800',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('shopper::status.pending'),
            self::Register => __('shopper::status.registered'),
            self::Completed => __('shopper::status.completed'),
            self::Cancelled => __('shopper::status.cancelled'),
            self::Paid => __('shopper::status.paid'),
        };
    }
}
