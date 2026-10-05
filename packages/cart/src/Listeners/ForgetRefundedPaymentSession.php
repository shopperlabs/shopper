<?php

declare(strict_types=1);

namespace Shopper\Cart\Listeners;

use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Core\Events\Payments\OrphanedPaymentRefunded;

final readonly class ForgetRefundedPaymentSession
{
    public function __construct(
        private CartManager $cartManager,
    ) {}

    public function handle(OrphanedPaymentRefunded $event): void
    {
        /** @var Cart|null $cart */
        $cart = resolve(CartContract::class)::query()->holdingPaymentReference($event->reference)->first();

        if ($cart === null) {
            return;
        }

        $this->cartManager->withPaymentSessionLock($cart, function () use ($cart, $event): void {
            $session = $cart->refresh()->payment_session;

            if (($session['reference'] ?? null) === $event->reference && ($event->amount === null || $event->amount >= $session['amount'])) {
                $this->cartManager->setPaymentSession($cart, null);
            }
        });
    }
}
