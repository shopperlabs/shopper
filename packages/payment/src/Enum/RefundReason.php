<?php

declare(strict_types=1);

namespace Shopper\Payment\Enum;

enum RefundReason: string
{
    case RequestedByCustomer = 'requested_by_customer';

    case Duplicate = 'duplicate';

    case Fraudulent = 'fraudulent';
}
