<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Paid()
 * @method static string Pending()
 * @method static string Completed()
 * @method static string Cancelled()
 */
enum PaymentStatus: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Paid = 'paid';

    case Pending = 'pending';

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('shopper-core::status.pending'),
            self::Completed => __('shopper-core::status.completed'),
            self::Cancelled => __('shopper-core::status.cancelled'),
            self::Paid => __('shopper-core::status.paid'),
        };
    }
}
