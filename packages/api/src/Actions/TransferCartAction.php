<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Shopper\Cart\Models\Cart;

final readonly class TransferCartAction
{
    /**
     * Attach a guest cart to a customer. Transferring a cart the customer
     * already owns is a no-op, so a retried login never fails; a cart owned
     * by another customer is refused.
     *
     * @throws AuthorizationException
     */
    public function execute(Cart $cart, int $customerId): void
    {
        if ($cart->customer_id === $customerId) {
            return;
        }

        if ($cart->customer_id !== null) {
            throw new AuthorizationException;
        }

        $cart->update(['customer_id' => $customerId]);
    }
}
