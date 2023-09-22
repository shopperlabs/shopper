<?php

declare(strict_types=1);

namespace Shopper\Core\Enum\Dimension;

use Shopper\Core\Traits\ArrayableEnum;

enum Volume: string
{
    use ArrayableEnum;

    case L = 'l';

    case ML = 'ml';

    case GAL = 'gal';

    case FLOZ = 'floz';
}
