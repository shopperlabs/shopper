<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Shopper\Api\Concerns\LocksPaymentSession;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\CartOwnedByAnotherCustomerException;
use Shopper\Cart\Models\Cart;
use Shopper\Http\Exceptions\ApiException;

final readonly class TransferCartAction
{
    use LocksPaymentSession;

    public function __construct(
        private CartManager $cartManager,
    ) {}

    /**
     * @throws AuthorizationException
     * @throws CartCompletedException
     * @throws ApiException
     */
    public function execute(Cart $cart, int|string $customerId): Cart
    {
        try {
            return $this->cartManager->transfer($cart, $customerId);
        } catch (CartOwnedByAnotherCustomerException) {
            throw new AuthorizationException;
        } catch (LockTimeoutException $exception) {
            throw $this->paymentSessionInProgress($exception);
        }
    }
}
