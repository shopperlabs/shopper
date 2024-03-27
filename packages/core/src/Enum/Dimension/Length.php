<?php

declare(strict_types=1);

namespace Shopper\Core\Enum\Dimension;

use Shopper\Core\Traits\ArrayableEnum;

enum Length: string
{
    use ArrayableEnum;

    case M = 'm';

    case CM = 'cm';

    case MM = 'mm';

    case FT = 'ft';

    case IN = 'in';
}
