<?php

declare(strict_types=1);

namespace Shopper\Enum;

use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Store()
 * @method static string Selling()
 * @method static string Shipping()
 * @method static string Team()
 */
enum SettingGroup: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Store = 'store';

    case Selling = 'selling';

    case Shipping = 'shipping';

    case Team = 'team';

    public function getLabel(): string
    {
        return match ($this) {
            self::Store => __('shopper::pages/settings/groups.store'),
            self::Selling => __('shopper::pages/settings/groups.selling'),
            self::Shipping => __('shopper::pages/settings/groups.shipping'),
            self::Team => __('shopper::pages/settings/groups.team'),
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::Store => 10,
            self::Selling => 20,
            self::Shipping => 30,
            self::Team => 40,
        };
    }
}
