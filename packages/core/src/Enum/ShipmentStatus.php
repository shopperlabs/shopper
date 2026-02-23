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
 * @method static string PickedUp()
 * @method static string InTransit()
 * @method static string AtSortingCenter()
 * @method static string OutForDelivery()
 * @method static string Delivered()
 * @method static string DeliveryFailed()
 * @method static string Returned()
 */
enum ShipmentStatus: string implements HasColor, HasIcon, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Pending = 'pending';

    case PickedUp = 'picked_up';

    case InTransit = 'in_transit';

    case AtSortingCenter = 'at_sorting_center';

    case OutForDelivery = 'out_for_delivery';

    case Delivered = 'delivered';

    case DeliveryFailed = 'delivery_failed';

    case Returned = 'returned';

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::PickedUp => 'info',
            self::InTransit => 'primary',
            self::AtSortingCenter => 'indigo',
            self::OutForDelivery => 'warning',
            self::Delivered => 'green',
            self::DeliveryFailed => 'danger',
            self::Returned => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'untitledui-file-check-02',
            self::PickedUp => 'untitledui-package',
            self::InTransit => 'heroicon-o-truck',
            self::AtSortingCenter => 'untitledui-building-07',
            self::OutForDelivery => 'untitledui-route',
            self::Delivered => 'untitledui-package-check',
            self::DeliveryFailed => 'heroicon-o-exclamation-circle',
            self::Returned => 'untitledui-reverse-left',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('shopper-core::status.shipment.pending'),
            self::PickedUp => __('shopper-core::status.shipment.picked_up'),
            self::InTransit => __('shopper-core::status.shipment.in_transit'),
            self::AtSortingCenter => __('shopper-core::status.shipment.at_sorting_center'),
            self::OutForDelivery => __('shopper-core::status.shipment.out_for_delivery'),
            self::Delivered => __('shopper-core::status.shipment.delivered'),
            self::DeliveryFailed => __('shopper-core::status.shipment.delivery_failed'),
            self::Returned => __('shopper-core::status.shipment.returned'),
        };
    }
}
