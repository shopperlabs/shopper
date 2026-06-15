<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Order()
 * @method static string Product()
 * @method static string Shipping()
 */
enum ExclusivityClass: string
{
    use HasEnumStaticMethods;

    case Order = 'order';

    case Product = 'product';

    case Shipping = 'shipping';
}
