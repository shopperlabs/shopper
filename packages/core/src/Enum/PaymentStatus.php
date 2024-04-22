<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

enum PaymentStatus: string
{
    case Paid = 'paid';

    case Completed = 'completed';

    case Cancelled = 'cancelled';
}
