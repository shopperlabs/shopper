<?php

declare(strict_types=1);

use Shopper\Cart\CartSessionManager;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Models\Currency;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->user = User::factory()->create();
    $this->sessionManager = resolve(CartSessionManager::class);
});

describe(CartSessionManager::class, function (): void {
    it('returns null when no cart in session and `auto_create` is false', function (): void {
        config()->set('shopper.cart.session.auto_create', false);

        expect($this->sessionManager->current())->toBeNull();
    });

    it('creates a cart when `auto_create` is true', function (): void {
        config()->set('shopper.cart.session.auto_create', true);

        $cart = $this->sessionManager->current();

        expect($cart)->toBeInstanceOf(Cart::class)
            ->and(Cart::query()->count())->toBe(1);
    });

    it('retrieves an existing cart from session', function (): void {
        $cart = $this->sessionManager->create(['currency_code' => 'USD']);

        $retrieved = $this->sessionManager->current();

        expect($retrieved)->toBeInstanceOf(Cart::class)
            ->and($retrieved->id)->toBe($cart->id);
    });

    it('creates a new cart when session cart is completed', function (): void {
        config()->set('shopper.cart.session.auto_create', true);

        $oldCart = $this->sessionManager->create(['currency_code' => 'USD']);
        $oldCart->update(['completed_at' => now()]);

        $newCart = $this->sessionManager->current();

        expect($newCart)->toBeInstanceOf(Cart::class)
            ->and($newCart->id)->not->toBe($oldCart->id);
    });

    it('associates a customer to the current cart', function (): void {
        $cart = $this->sessionManager->create(['currency_code' => 'USD']);

        $this->sessionManager->associate($this->user);

        expect($cart->refresh()->customer_id)->toBe($this->user->id);
    });

    it('forgets the cart from session', function (): void {
        config()->set('shopper.cart.session.auto_create', false);

        $this->sessionManager->create(['currency_code' => 'USD']);

        $this->sessionManager->forget();

        expect($this->sessionManager->current())->toBeNull();
    });

    it('switches to another cart via `use()`', function (): void {
        $this->sessionManager->create(['currency_code' => 'USD']);
        $cart2 = Cart::query()->create(['currency_code' => 'USD']);

        $this->sessionManager->use($cart2);

        expect($this->sessionManager->current()->id)->toBe($cart2->id);
    });
})->group('cart', 'cart-session');
