<?php

declare(strict_types=1);

namespace Shopper\Enum;

enum FeatureState: string
{
    case ENABLED = 'enabled';

    case DISABLED = 'disabled';
}
