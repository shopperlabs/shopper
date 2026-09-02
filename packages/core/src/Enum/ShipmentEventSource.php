<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

enum ShipmentEventSource: string
{
    case Manual = 'manual';

    case Carrier = 'carrier';
}
