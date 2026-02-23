<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Unfulfilled()
 * @method static string PartiallyShipped()
 * @method static string Shipped()
 * @method static string PartiallyDelivered()
 * @method static string Delivered()
 * @method static string PartiallyReturned()
 * @method static string Returned()
 */
enum ShippingStatus: string implements HasColor, HasIcon, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Unfulfilled = 'unfulfilled';

    case PartiallyShipped = 'partially_shipped';

    case Shipped = 'shipped';

    case PartiallyDelivered = 'partially_delivered';

    case Delivered = 'delivered';

    case PartiallyReturned = 'partially_returned';

    case Returned = 'returned';

    public function getColor(): string
    {
        return match ($this) {
            self::Unfulfilled => 'warning',
            self::PartiallyShipped => 'info',
            self::Shipped => 'indigo',
            self::PartiallyDelivered => 'primary',
            self::Delivered => 'success',
            self::PartiallyReturned => 'orange',
            self::Returned => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Unfulfilled => 'untitledui-hourglass-03',
            self::PartiallyShipped => 'untitledui-package',
            self::Shipped => 'heroicon-o-truck',
            self::PartiallyDelivered => 'untitledui-route',
            self::Delivered => 'untitledui-package-check',
            self::PartiallyReturned, self::Returned => 'untitledui-reverse-left',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Unfulfilled => __('shopper-core::status.shipping.unfulfilled'),
            self::PartiallyShipped => __('shopper-core::status.shipping.partially_shipped'),
            self::Shipped => __('shopper-core::status.shipping.shipped'),
            self::PartiallyDelivered => __('shopper-core::status.shipping.partially_delivered'),
            self::Delivered => __('shopper-core::status.shipping.delivered'),
            self::PartiallyReturned => __('shopper-core::status.shipping.partially_returned'),
            self::Returned => __('shopper-core::status.shipping.returned'),
        };
    }
}
