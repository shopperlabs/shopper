<?php

declare(strict_types=1);

namespace Shopper\Payment\Enum;

enum TransactionStatus: string
{
    case Pending = 'pending';

    case Success = 'success';

    case Failed = 'failed';
}
