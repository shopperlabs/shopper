<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Pending()
 * @method static string ForwardedToSupplier()
 * @method static string Processing()
 * @method static string Shipped()
 * @method static string Delivered()
 * @method static string Cancelled()
 */
enum FulfillmentStatus: string implements HasColor, HasIcon, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Pending = 'pending';

    case ForwardedToSupplier = 'forwarded_to_supplier';

    case Processing = 'processing';

    case Shipped = 'shipped';

    case Delivered = 'delivered';

    case Cancelled = 'cancelled';

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::ForwardedToSupplier => 'info',
            self::Processing => 'primary',
            self::Shipped => 'indigo',
            self::Delivered => 'success',
            self::Cancelled => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'untitledui-hourglass-03',
            self::ForwardedToSupplier => 'untitledui-send-03',
            self::Processing => 'untitledui-loading-02',
            self::Shipped => 'heroicon-o-truck',
            self::Delivered => 'untitledui-package-check',
            self::Cancelled => 'heroicon-o-minus-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('shopper-core::status.fulfillment.pending'),
            self::ForwardedToSupplier => __('shopper-core::status.fulfillment.forwarded'),
            self::Processing => __('shopper-core::status.fulfillment.processing'),
            self::Shipped => __('shopper-core::status.fulfillment.shipped'),
            self::Delivered => __('shopper-core::status.fulfillment.delivered'),
            self::Cancelled => __('shopper-core::status.fulfillment.cancelled'),
        };
    }
}
