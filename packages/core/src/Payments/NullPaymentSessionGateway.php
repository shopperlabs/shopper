<?php

declare(strict_types=1);

namespace Shopper\Core\Payments;

use Shopper\Core\Contracts\PaymentSessionGateway;
use Shopper\Core\Models\Contracts\Order;

final class NullPaymentSessionGateway implements PaymentSessionGateway
{
    public function collected(array $session): ?bool
    {
        return false;
    }

    public function release(array $session): bool
    {
        return true;
    }

    public function refund(array $session): bool
    {
        return true;
    }

    public function releaseOrder(Order $order): bool
    {
        return true;
    }
}
