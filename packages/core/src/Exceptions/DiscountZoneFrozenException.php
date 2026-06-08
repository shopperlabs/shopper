<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use RuntimeException;

final class DiscountZoneFrozenException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'Cannot change zone on a fixed-amount discount that has already been used. '
            .'Currency consistency requires the zone to remain frozen after first usage.'
        );
    }
}
