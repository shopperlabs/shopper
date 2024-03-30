<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

enum AddressType: string
{
    case BILLING = 'billing';

    case SHIPPING = 'shipping';
}
