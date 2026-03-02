<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasColor;
use Shopper\Core\Contracts\HasIcon;
use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string New()
 * @method static string Processing()
 * @method static string Completed()
 * @method static string Cancelled()
 * @method static string Archived()
 */
enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case New = 'new';

    case Processing = 'processing';

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    case Archived = 'archived';

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'info',
            self::Processing => 'primary',
            self::Completed => 'teal',
            self::Cancelled => 'danger',
            self::Archived => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::New => 'untitledui-star',
            self::Processing => 'untitledui-loading-02',
            self::Completed => 'heroicon-o-check-badge',
            self::Cancelled => 'heroicon-o-minus-circle',
            self::Archived => 'untitledui-archive',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::New => __('shopper-core::status.new'),
            self::Processing => __('shopper-core::status.processing'),
            self::Completed => __('shopper-core::status.completed'),
            self::Cancelled => __('shopper-core::status.cancelled'),
            self::Archived => __('shopper-core::status.archived'),
        };
    }
}
