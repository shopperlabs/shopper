<?php

declare(strict_types=1);

namespace Shopper\Core\Enum\Dimension;

use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string KG()
 * @method static string G()
 * @method static string LBS()
 */
enum Weight: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case KG = 'kg';

    case G = 'g';

    case LBS = 'lbs';

    public function getLabel(): string
    {
        return match ($this) {
            self::KG => 'kg',
            self::G => 'g',
            self::LBS => 'lbs',
        };
    }
}
