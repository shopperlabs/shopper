<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;

enum OrderStatus: string implements HasLabel, HasColor
{
    use ArrayableEnum;

    case New = 'new';

    case Shipped = 'shipped';

    case Delivered = 'delivered';

    case Pending = 'pending';

    case Register = 'registered';

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::New => 'info',
            self::Cancelled => 'danger',
            self::Completed => 'teal',
            self::Delivered => 'sky',
            self::Pending => 'warning',
            self::Register => 'primary',
            self::Shipped => 'indigo',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::New => __('shopper-core::status.new'),
            self::Completed => __('shopper-core::status.completed'),
            self::Cancelled => __('shopper-core::status.cancelled'),
            self::Delivered => __('shopper-core::status.delivered'),
            self::Pending => __('shopper-core::status.pending'),
            self::Register => __('shopper-core::status.registered'),
            self::Shipped => __('shopper-core::status.shipped'),
        };
    }
}
