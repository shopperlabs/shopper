<?php

declare(strict_types=1);

namespace Shopper\Core\Enum\Dimension;

use Shopper\Core\Traits\ArrayableEnum;

enum Weight: string
{
    use ArrayableEnum;

    case KG = 'kg';

    case G = 'g';

    case LBS = 'lbs';
}
