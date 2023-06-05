<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

enum OrderStatus
{
    case PENDING;
    case REGISTER;
    case PAID;
    case COMPLETED;
    case CANCELLED;

    public function value(): string
    {
        return match ($this)
        {
            self::PENDING => __('shopper::status.pending'),
            self::REGISTER => __('shopper::status.registered'),
            self::COMPLETED => __('shopper::status.completed'),
            self::CANCELLED => __('shopper::status.cancelled'),
            self::PAID => __('shopper::status.paid'),
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::PENDING => 'border-yellow-200 bg-yellow-100 text-yellow-800',
            self::REGISTER => 'border-blue-200 bg-blue-100 text-blue-800',
            self::PAID => 'border-green-200 bg-green-100 text-green-800',
            self::CANCELLED => 'border-pink-200 bg-pink-100 text-pink-800',
            self::COMPLETED => 'border-purple-200 bg-purple-100 text-purple-800',
        };
    }

    public static function values(): array
    {
        return [
            OrderStatus::PENDING->value() => __('shopper::status.pending'),
            OrderStatus::REGISTER->value() => __('shopper::status.registered'),
            OrderStatus::COMPLETED->value() => __('shopper::status.completed'),
            OrderStatus::CANCELLED->value() => __('shopper::status.cancelled'),
            OrderStatus::PAID->value() => __('shopper::status.paid'),
        ];
    }
}
