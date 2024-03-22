<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

enum CollectionType: string
{
    case MANUAL = 'manual';

    case AUTO = 'auto';
}
