<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Code()
 * @method static string Automatic()
 */
enum PromotionSource: string
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Code = 'code';

    case Automatic = 'automatic';
}
