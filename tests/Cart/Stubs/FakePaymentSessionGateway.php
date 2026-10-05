<?php

declare(strict_types=1);

namespace Tests\Cart\Stubs;

use Shopper\Core\Contracts\PaymentSessionGateway;
use Shopper\Core\Exceptions\PaymentProviderUnavailableException;
use Shopper\Core\Models\Contracts\Order;

final class FakePaymentSessionGateway implements PaymentSessionGateway
{
    public bool $paid = false;

    public int $releases = 0;

    public int $refunds = 0;

    /** @var list<int|string> */
    public array $unavailableOrders = [];

    public function collected(array $session): ?bool
    {
        return $this->paid;
    }

    public function release(array $session): bool
    {
        $this->releases++;

        return ! $this->paid;
    }

    public function refund(array $session): bool
    {
        $this->refunds++;

        return true;
    }

    public function releaseOrder(Order $order): bool
    {
        if (in_array($order->getKey(), $this->unavailableOrders, true)) {
            throw new PaymentProviderUnavailableException;
        }

        return ! $this->paid;
    }
}
