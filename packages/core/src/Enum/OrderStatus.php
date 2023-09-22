<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';

    case REGISTER = 'registered';

    case PAID = 'completed';

    case COMPLETED = 'cancelled';

    case CANCELLED = 'paid';

    public function badge(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::REGISTER => 'bg-primary-100 text-primary-800',
            self::PAID => 'bg-green-100 text-green-800',
            self::CANCELLED => 'bg-pink-100 text-pink-800',
            self::COMPLETED => 'bg-purple-100 text-purple-800',
        };
    }

    public function translateValue(): string
    {
        return match ($this) {
            self::PENDING => __('shopper::status.pending'),
            self::REGISTER => __('shopper::status.registered'),
            self::COMPLETED => __('shopper::status.completed'),
            self::CANCELLED => __('shopper::status.cancelled'),
            self::PAID => __('shopper::status.paid'),
        };
    }
}
