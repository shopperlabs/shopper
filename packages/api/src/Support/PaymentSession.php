<?php

declare(strict_types=1);

namespace Shopper\Api\Support;

use Shopper\Cart\Models\Cart;
use Shopper\Payment\DataTransferObjects\PaymentResult;

/**
 * The payment context a storefront needs to collect a payment for a cart:
 * the driver result (client secret, redirect url, ...) bound to the cart it
 * was priced from. The reference identifies the session across requests.
 */
final readonly class PaymentSession
{
    public function __construct(
        public Cart $cart,
        public string $driver,
        public PaymentResult $result,
    ) {}

    public function id(): string
    {
        return $this->result->reference ?? (string) $this->cart->public_id;
    }
}
