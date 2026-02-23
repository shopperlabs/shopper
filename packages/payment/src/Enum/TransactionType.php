<?php

declare(strict_types=1);

namespace Shopper\Payment\Enum;

enum TransactionType: string
{
    case Initiate = 'initiate';

    case Authorize = 'authorize';

    case Capture = 'capture';

    case Refund = 'refund';

    case Cancel = 'cancel';
}
