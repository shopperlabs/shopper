<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasColor;
use Shopper\Core\Contracts\HasIcon;
use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Pending()
 * @method static string Authorized()
 * @method static string Paid()
 * @method static string PartiallyRefunded()
 * @method static string Refunded()
 * @method static string Voided()
 */
enum PaymentStatus: string implements HasColor, HasIcon, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Pending = 'pending';

    case Authorized = 'authorized';

    case Paid = 'paid';

    case PartiallyRefunded = 'partially_refunded';

    case Refunded = 'refunded';

    case Voided = 'voided';

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Authorized => 'info',
            self::Paid => 'success',
            self::PartiallyRefunded => 'orange',
            self::Refunded => 'gray',
            self::Voided => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'untitledui-hourglass-03',
            self::Authorized => 'untitledui-shield-tick',
            self::Paid => 'heroicon-o-banknotes',
            self::PartiallyRefunded, self::Refunded => 'untitledui-coins-swap-02',
            self::Voided => 'heroicon-o-minus-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('shopper-core::status.payment.pending'),
            self::Authorized => __('shopper-core::status.payment.authorized'),
            self::Paid => __('shopper-core::status.payment.paid'),
            self::PartiallyRefunded => __('shopper-core::status.payment.partially_refunded'),
            self::Refunded => __('shopper-core::status.payment.refunded'),
            self::Voided => __('shopper-core::status.payment.voided'),
        };
    }
}
