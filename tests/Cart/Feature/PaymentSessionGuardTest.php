<?php

declare(strict_types=1);

use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->cartManager = resolve(CartManager::class);
});

describe('PaymentSessionGuardTest', function (): void {
    it('ignores `payment_session` passed to mass assignment', function (): void {
        $cart = Cart::factory()->create(['currency_code' => 'USD']);

        $cart->update(['payment_session' => ['amount' => 1, 'reference' => 'pi_fake']]);
        $cart->fill(['payment_session' => ['amount' => 1, 'reference' => 'pi_fake']]);

        expect($cart->refresh()->payment_session)->toBeNull();
    });

    it('writes `payment_session` only through the manager setter', function (): void {
        $cart = Cart::factory()->create(['currency_code' => 'USD']);

        $this->cartManager->setPaymentSession($cart, ['amount' => 2500, 'reference' => 'pi_real']);

        expect($cart->refresh()->payment_session)->toMatchArray([
            'amount' => 2500,
            'reference' => 'pi_real',
        ]);
    });
})->group('cart');
