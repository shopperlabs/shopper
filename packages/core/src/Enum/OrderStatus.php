<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string New()
 * @method static string Shipped()
 * @method static string Delivered()
 * @method static string Paid()
 * @method static string Pending()
 * @method static string Register()
 * @method static string Completed()
 * @method static string Cancelled()
 */
enum OrderStatus: string implements HasColor, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case New = 'new';

    case Shipped = 'shipped';

    case Delivered = 'delivered';

    case Pending = 'pending';

    case Paid = 'paid';

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
            self::Paid => 'green',
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
            self::Paid => __('shopper-core::status.paid'),
            self::Pending => __('shopper-core::status.pending'),
            self::Register => __('shopper-core::status.registered'),
            self::Shipped => __('shopper-core::status.shipped'),
        };
    }
}
