<?php

declare(strict_types=1);

namespace Shopper\Core\Enum\Dimension;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string M()
 * @method static string CM()
 * @method static string MM()
 * @method static string FT()
 * @method static string IN()
 */
enum Length: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case M = 'm';

    case CM = 'cm';

    case MM = 'mm';

    case FT = 'ft';

    case IN = 'in';

    public function getLabel(): string
    {
        return match ($this) {
            self::M => 'm',
            self::CM => 'cm',
            self::MM => 'mm',
            self::FT => 'ft',
            self::IN => 'in',
        };
    }
}
