<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Shopper\Core\Exceptions\PaymentProviderUnavailableException;
use Shopper\Core\Models\Contracts\Order;

interface PaymentSessionGateway
{
    /**
     * Whether the provider collected the payment session: null when the
     * provider cannot tell.
     *
     * @param  array<string, mixed>  $session
     *
     * @throws PaymentProviderUnavailableException
     */
    public function collected(array $session): ?bool;

    /**
     * Make sure the payment session can no longer collect anything: false
     * when it was, or may have been, collected.
     *
     * @param  array<string, mixed>  $session
     *
     * @throws PaymentProviderUnavailableException
     */
    public function release(array $session): bool;

    /**
     * Give back what the payment session collected, voiding an authorization
     * or refunding a capture: false when the money could not be given back.
     *
     * @param  array<string, mixed>  $session
     *
     * @throws PaymentProviderUnavailableException
     */
    public function refund(array $session): bool;

    /**
     * Release the payment of an order still awaiting it: false when the
     * payment was, or may have been, collected.
     *
     * @throws PaymentProviderUnavailableException
     */
    public function releaseOrder(Order $order): bool;
}
