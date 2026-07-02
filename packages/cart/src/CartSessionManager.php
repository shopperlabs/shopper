<?php

declare(strict_types=1);

namespace Shopper\Cart;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Session\SessionManager;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\Contracts\Cart as CartContract;

final class CartSessionManager
{
    public function __construct(
        private readonly SessionManager $session,
    ) {}

    public function current(): ?Cart
    {
        $cartId = $this->session->get($this->sessionKey());

        if ($cartId) {
            /** @var Cart|null $cart */
            $cart = resolve(CartContract::class)::query()->find($cartId);

            if ($cart && ! $cart->isCompleted()) {
                return $cart;
            }
        }

        if (config('shopper.cart.session.auto_create')) {
            return $this->create();
        }

        return null;
    }

    /**
     * @param  array{currency_code?: string, channel_id?: int, zone_id?: int}  $attributes
     */
    public function create(array $attributes = []): Cart
    {
        /** @var Cart $cart */
        $cart = resolve(CartContract::class)::query()->create(array_merge([
            'currency_code' => shopper_currency(),
        ], $attributes));

        $this->session->put($this->sessionKey(), $cart->id);

        return $cart;
    }

    public function use(Cart $cart): void
    {
        $this->session->put($this->sessionKey(), $cart->id);
    }

    public function forget(): void
    {
        $this->session->forget($this->sessionKey());
    }

    /**
     * Attach the session cart to the user who just signed in, folding it into
     * the cart they already owned when one exists and repointing the session
     * to the surviving cart.
     */
    public function associate(Authenticatable $user): void
    {
        $cart = $this->current();

        if (! $cart) {
            return;
        }

        if ($cart->customer_id === $user->getAuthIdentifier()) {
            return;
        }

        /** @var Cart|null $existing */
        $existing = resolve(CartContract::class)::query()
            ->where('customer_id', $user->getAuthIdentifier())
            ->whereNull('completed_at')
            ->latest()
            ->first();

        if ($existing) {
            $merged = resolve(CartManager::class)->merge($cart, $existing);

            $this->use($merged);

            return;
        }

        $cart->update(['customer_id' => $user->getAuthIdentifier()]);
    }

    private function sessionKey(): string
    {
        return config('shopper.cart.session.key', 'shopper_cart');
    }
}
