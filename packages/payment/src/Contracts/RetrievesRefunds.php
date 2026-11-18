<?php

declare(strict_types=1);

namespace Shopper\Payment\Contracts;

use Shopper\Payment\DataTransferObjects\PaymentResult;

interface RetrievesRefunds
{
    public function retrieveRefund(string $refundId): PaymentResult;
}
