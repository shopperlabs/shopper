<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\Contracts\Cart as CartContract;

final readonly class TransferCartAction
{
    public function __construct(
        private CartManager $cartManager,
        private RevalidateCartCouponAction $revalidateCoupon,
    ) {}

    /**
     * Attach a guest cart to a customer, folding it into the cart the
     * customer already owns when one exists so nothing they saved earlier is
     * lost. Transferring a cart the customer already owns is a no-op, so a
     * retried login never fails; a cart owned by another customer is refused.
     * Once the cart belongs to the customer, an applied coupon is
     * re-validated against their redemption history and dropped if a
     * per-customer limit now rejects it.
     *
     * @throws AuthorizationException
     */
    public function execute(Cart $cart, int $customerId): Cart
    {
        if ($cart->customer_id === $customerId) {
            return $cart;
        }

        if ($cart->customer_id !== null) {
            throw new AuthorizationException;
        }

        /** @var Cart|null $existing */
        $existing = resolve(CartContract::class)::query()
            ->where('customer_id', $customerId)
            ->whereNull('completed_at')
            ->latest()
            ->first();

        if ($existing) {
            $cart = $this->cartManager->merge($cart, $existing);
        } else {
            $cart->update(['customer_id' => $customerId]);
        }

        $this->revalidateCoupon->execute($cart);

        return $cart;
    }
}
