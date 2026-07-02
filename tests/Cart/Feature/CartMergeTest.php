<?php

declare(strict_types=1);

use Shopper\Cart\CartManager;
use Shopper\Cart\CartSessionManager;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Tests\Core\Stubs\User;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->cartManager = resolve(CartManager::class);
    $this->inventory = Inventory::factory()->create();
    $this->user = User::factory()->create();

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create(['amount' => 1000, 'currency_id' => $this->currency->id]);
    $this->product->load('prices');
    $this->product->mutateStock($this->inventory->id, 100);

    $this->guestCart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->userCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $this->user->id]);
});

describe('CartManager::merge', function (): void {
    it('sums the quantities of a purchasable present in both carts', function (): void {
        $this->cartManager->add($this->guestCart, $this->product, quantity: 2);
        $this->cartManager->add($this->userCart, $this->product, quantity: 3);

        $merged = $this->cartManager->merge($this->guestCart, $this->userCart);

        expect($merged->id)->toBe($this->userCart->id)
            ->and($merged->lines()->count())->toBe(1)
            ->and($merged->lines()->first()->quantity)->toBe(5)
            ->and(Cart::query()->find($this->guestCart->id))->toBeNull();
    });

    it('moves lines the customer cart did not have', function (): void {
        $other = Product::factory()->standard()->create();
        $other->prices()->create(['amount' => 500, 'currency_id' => $this->currency->id]);
        $other->load('prices');
        $other->mutateStock($this->inventory->id, 50);

        $this->cartManager->add($this->guestCart, $other, quantity: 1);
        $this->cartManager->add($this->userCart, $this->product, quantity: 1);

        $merged = $this->cartManager->merge($this->guestCart, $this->userCart);

        expect($merged->lines()->count())->toBe(2)
            ->and($merged->lines()->pluck('purchasable_id'))->toContain($other->id);
    });

    it('re-prices moved lines when the carts use different currencies', function (): void {
        $eur = Currency::query()->where('code', 'EUR')->first();
        $this->product->prices()->create(['amount' => 900, 'currency_id' => $eur->id]);
        $this->product->load('prices');

        $eurCart = Cart::factory()->create(['currency_code' => 'EUR', 'customer_id' => $this->user->id]);

        $this->cartManager->add($this->guestCart, $this->product, quantity: 1);

        $merged = $this->cartManager->merge($this->guestCart, $eurCart);

        expect($merged->lines()->first()->unit_price_amount)->toBe(900);
    });

    it('carries applied promotions over without duplicating', function (): void {
        $discount = Discount::factory()->create(['code' => 'WELCOME', 'is_active' => true]);

        $this->guestCart->promotions()->create([
            'discount_id' => $discount->id,
            'source' => PromotionSource::Code->value,
            'code' => 'WELCOME',
        ]);
        $this->userCart->promotions()->create([
            'discount_id' => $discount->id,
            'source' => PromotionSource::Code->value,
            'code' => 'WELCOME',
        ]);

        $merged = $this->cartManager->merge($this->guestCart, $this->userCart);

        expect($merged->promotions()->count())->toBe(1);
    });
});

describe('CartSessionManager::associate', function (): void {
    it('merges the session cart into the cart the user already owns', function (): void {
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);

        $this->cartManager->add($this->guestCart, $this->product, quantity: 2);
        $this->cartManager->add($this->userCart, $this->product, quantity: 1);

        $session->associate($this->user);

        expect($session->current()->id)->toBe($this->userCart->id)
            ->and($this->userCart->lines()->first()->quantity)->toBe(3);
    });

    it('attaches the session cart when the user owns no cart', function (): void {
        $freshUser = User::factory()->create();
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);

        $session->associate($freshUser);

        expect($this->guestCart->refresh()->customer_id)->toBe($freshUser->id);
    });
});
