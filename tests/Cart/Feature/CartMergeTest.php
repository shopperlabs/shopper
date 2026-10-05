<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Sleep;
use Shopper\Cart\CartManager;
use Shopper\Cart\CartSessionManager;
use Shopper\Cart\Exceptions\MissingPriceException;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Contracts\PaymentSessionGateway;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderPromotion;
use Shopper\Core\Models\Product;
use Tests\Cart\Stubs\FakePaymentSessionGateway;
use Tests\Core\Stubs\User;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->gateway = new FakePaymentSessionGateway;
    $this->app->instance(PaymentSessionGateway::class, $this->gateway);
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
        $this->userCart->update(['shipping_option_id' => 'main-carrier:standard', 'shipping_amount' => 500]);
        $this->cartManager->setPaymentSession($this->userCart, ['reference' => 'pi_1', 'amount' => 1500]);

        $merged = $this->cartManager->merge($this->guestCart, $this->userCart);

        expect($merged->lines()->count())->toBe(2)
            ->and($merged->lines()->pluck('purchasable_id'))->toContain($other->id)
            ->and($merged->shipping_option_id)->toBeNull()
            ->and($merged->shipping_amount)->toBeNull()
            ->and($merged->payment_session)->toBeNull();
    });

    it('is a no-op when the source cart is gone by the time the lock is acquired', function (): void {
        $this->cartManager->add($this->userCart, $this->product, quantity: 1);
        $this->guestCart->delete();

        $merged = $this->cartManager->merge($this->guestCart, $this->userCart);

        expect($merged->is($this->userCart))->toBeTrue()
            ->and($merged->lines()->first()->quantity)->toBe(1);
    });

    it('refuses a line without a price in the target currency', function (): void {
        $eurCart = Cart::factory()->create(['currency_code' => 'EUR', 'customer_id' => $this->user->id]);
        $this->cartManager->add($this->guestCart, $this->product, quantity: 1);

        $this->cartManager->merge($this->guestCart, $eurCart);
    })->throws(MissingPriceException::class);

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

    it('claims the session cart whole when a line holds other metadata in the cart the user owns', function (): void {
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);

        $this->cartManager->add($this->guestCart, $this->product, metadata: ['engraving' => 'Bob']);
        $this->cartManager->add($this->userCart, $this->product, metadata: ['engraving' => 'Alice']);

        $session->associate($this->user);

        expect($session->current()->id)->toBe($this->guestCart->id)
            ->and($this->guestCart->refresh()->customer_id)->toBe($this->user->id)
            ->and($this->guestCart->lines()->sole()->metadata)->toBe(['engraving' => 'Bob'])
            ->and($this->userCart->lines()->sole()->metadata)->toBe(['engraving' => 'Alice']);
    });

    it('claims the session cart whole when a line has no price in the currency of the cart the user owns', function (): void {
        $user = User::factory()->create();
        Cart::factory()->create(['currency_code' => 'EUR', 'customer_id' => $user->id]);
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);

        $this->cartManager->add($this->guestCart, $this->product, quantity: 2);

        $session->associate($user);

        expect($session->current()->id)->toBe($this->guestCart->id)
            ->and($this->guestCart->refresh()->customer_id)->toBe($user->id)
            ->and($this->guestCart->lines()->first()->unit_price_amount)->toBe(1000);
    });

    it('releases the unpaid payment session of the session cart it claims and reprices it', function (): void {
        $line = $this->cartManager->add($this->guestCart, $this->product);
        $this->cartManager->setPaymentSession($this->guestCart, ['driver' => 'stripe', 'reference' => 'pi_guest', 'amount' => 1000]);
        $this->product->prices()->update(['amount' => 2000]);

        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);
        $session->associate($this->user);

        expect($this->guestCart->refresh())
            ->customer_id->toBe($this->user->id)
            ->payment_session->toBeNull()
            ->and($line->refresh()->unit_price_amount)->toBe(2000);
    });

    it('keeps a paid session cart as a guest cart in the session', function (): void {
        $line = $this->cartManager->add($this->guestCart, $this->product);
        $this->cartManager->setPaymentSession($this->guestCart, ['driver' => 'stripe', 'reference' => 'pi_paid', 'amount' => 1000]);
        $this->gateway->paid = true;
        $this->product->prices()->update(['amount' => 2000]);

        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);
        $session->associate($this->user);

        expect($session->current()->id)->toBe($this->guestCart->id)
            ->and($this->guestCart->refresh()->customer_id)->toBeNull()
            ->and($this->guestCart->payment_session['reference'])->toBe('pi_paid')
            ->and($line->refresh()->unit_price_amount)->toBe(1000);
    });

    it('never merges into a cart the user owns with an open payment session', function (): void {
        $owned = $this->cartManager->add($this->userCart, $this->product);
        $this->cartManager->setPaymentSession($this->userCart, ['driver' => 'stripe', 'reference' => 'pi_owned', 'amount' => 1000]);
        $this->cartManager->add($this->guestCart, $this->product);

        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);
        $session->associate($this->user);

        expect($session->current()->id)->toBe($this->guestCart->id)
            ->and($this->userCart->refresh()->payment_session['reference'])->toBe('pi_owned')
            ->and($owned->refresh()->quantity)->toBe(1)
            ->and($this->guestCart->refresh()->customer_id)->toBe($this->user->id);
    });

    it('leaves the session cart a guest cart while its payment session opens', function (): void {
        Sleep::fake(syncWithCarbon: true);

        $this->cartManager->add($this->guestCart, $this->product);
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);

        Cache::lock("cart:payment-session:{$this->guestCart->public_id}", 30)->get();

        $session->associate($this->user);

        expect($session->current()->id)->toBe($this->guestCart->id)
            ->and($this->guestCart->refresh()->customer_id)->toBeNull()
            ->and($this->userCart->lines()->count())->toBe(0);
    });

    it('claims a session cart whose payment session opened after the login read it', function (): void {
        $line = $this->cartManager->add($this->guestCart, $this->product);
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);
        $cartId = $this->guestCart->id;
        $opened = false;

        Cart::retrieved(function (Cart $read) use ($cartId, &$opened): void {
            if ($read->id === $cartId && ! $opened) {
                $opened = true;
                DB::table($read->getTable())->where('id', $cartId)->update([
                    'payment_session' => json_encode(['driver' => 'stripe', 'reference' => 'pi_late', 'amount' => 1000]),
                ]);
            }
        });

        $session->associate($this->user);

        expect($this->guestCart->refresh()->payment_session)->toBeNull()
            ->and($this->gateway->releases)->toBe(1)
            ->and($this->guestCart->customer_id)->toBe($this->user->id)
            ->and($line->refresh()->cart_id)->toBe($this->guestCart->id);
    });

    it('leaves the session cart a guest cart while a payment session opens on the cart the user owns', function (): void {
        Sleep::fake(syncWithCarbon: true);

        $line = $this->cartManager->add($this->guestCart, $this->product, quantity: 2);
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);

        Cache::lock("cart:payment-session:{$this->userCart->public_id}", 30)->get();

        $session->associate($this->user);

        expect($session->current()->id)->toBe($this->guestCart->id)
            ->and($this->guestCart->refresh()->customer_id)->toBeNull()
            ->and($line->refresh()->cart_id)->toBe($this->guestCart->id);
    });

    it('drops a session cart owned by another customer instead of handing it over', function (): void {
        $other = User::factory()->create();
        $foreign = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $other->id]);
        $session = resolve(CartSessionManager::class);
        $session->use($foreign);

        $session->associate($this->user);

        expect($foreign->refresh()->customer_id)->toBe($other->id)
            ->and(session('shopper_cart'))->toBeNull();
    });

    it('drops a session cart another customer claimed while the login waited for it', function (): void {
        $other = User::factory()->create();
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);
        $cartId = $this->guestCart->id;
        $claimed = false;

        Cart::retrieved(function (Cart $read) use ($cartId, $other, &$claimed): void {
            if ($read->id === $cartId && ! $claimed) {
                $claimed = true;
                DB::table($read->getTable())->where('id', $cartId)->update(['customer_id' => $other->id]);
            }
        });

        $session->associate($this->user);

        expect($this->guestCart->refresh()->customer_id)->toBe($other->id)
            ->and(session('shopper_cart'))->toBeNull();
    });

    it('leaves a session cart alone when it completes while the login waits for its lock', function (): void {
        $line = $this->cartManager->add($this->guestCart, $this->product);
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);
        $cartId = $this->guestCart->id;
        $completed = false;

        Cart::retrieved(function (Cart $read) use ($cartId, &$completed): void {
            if ($read->id === $cartId && ! $completed) {
                $completed = true;
                DB::table($read->getTable())->where('id', $cartId)->update(['completed_at' => now()]);
            }
        });

        $session->associate(User::factory()->create());

        expect($this->guestCart->refresh()->customer_id)->toBeNull()
            ->and($line->refresh()->cart_id)->toBe($this->guestCart->id)
            ->and(session('shopper_cart'))->toBeNull();
    });

    it('keeps a session cart that a concurrent login already gave the same user while this one waited for it', function (): void {
        Sleep::fake(syncWithCarbon: true);
        Exceptions::fake();
        $freshUser = User::factory()->create();
        $line = $this->cartManager->add($this->guestCart, $this->product, quantity: 2);
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);
        $cartId = $this->guestCart->id;
        $claimed = false;

        Cart::retrieved(function (Cart $read) use ($cartId, $freshUser, &$claimed): void {
            if ($read->id === $cartId && ! $claimed) {
                $claimed = true;
                DB::table($read->getTable())->where('id', $cartId)->update(['customer_id' => $freshUser->id]);
            }
        });

        $session->associate($freshUser);

        Exceptions::assertNothingReported();

        expect($this->guestCart->refresh()->customer_id)->toBe($freshUser->id)
            ->and($line->refresh()->quantity)->toBe(2)
            ->and(session('shopper_cart'))->toBe($this->guestCart->id);
    });

    it('forgets a session cart another login merged away while this one waited for it', function (): void {
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);
        $cartId = $this->guestCart->id;

        Cart::retrieved(function (Cart $read) use ($cartId): void {
            if ($read->id === $cartId) {
                DB::table($read->getTable())->where('id', $cartId)->delete();
            }
        });

        $session->associate($this->user);

        expect(session('shopper_cart'))->toBeNull()
            ->and($this->userCart->refresh()->customer_id)->toBe($this->user->id);
    });

    it('claims the session cart when the cart the user owns is deleted while the login waits for it', function (): void {
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);
        $userCartId = $this->userCart->id;

        Cart::retrieved(function (Cart $read) use ($userCartId): void {
            if ($read->id === $userCartId) {
                DB::table($read->getTable())->where('id', $userCartId)->delete();
            }
        });

        $session->associate($this->user);

        expect($this->guestCart->refresh()->customer_id)->toBe($this->user->id)
            ->and(session('shopper_cart'))->toBe($this->guestCart->id);
    });

    it('claims the session cart when the cart the user owns is deleted as the merge starts', function (): void {
        $this->cartManager->add($this->guestCart, $this->product);
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);
        $userCartId = $this->userCart->id;
        $reads = 0;

        Cart::retrieved(function (Cart $read) use ($userCartId, &$reads): void {
            if ($read->id === $userCartId && ++$reads === 2) {
                DB::table($read->getTable())->where('id', $userCartId)->delete();
            }
        });

        $session->associate($this->user);

        expect($this->guestCart->refresh()->customer_id)->toBe($this->user->id)
            ->and($this->guestCart->lines()->count())->toBe(1)
            ->and(session('shopper_cart'))->toBe($this->guestCart->id);
    });

    it('recognises the owner of a cart whatever type the database returns for the customer id', function (): void {
        $cart = Cart::factory()->make(['customer_id' => '42']);

        expect($cart->belongsToCustomer(42))->toBeTrue()
            ->and($cart->belongsToCustomer('42'))->toBeTrue()
            ->and($cart->belongsToCustomer(4))->toBeFalse()
            ->and($cart->belongsToCustomer(null))->toBeFalse()
            ->and(Cart::factory()->make(['customer_id' => null])->belongsToCustomer(null))->toBeFalse();
    });

    it('never fails the login when the cart cannot be recalculated', function (): void {
        Exceptions::fake();
        $this->cartManager->add($this->guestCart, $this->product);
        $this->guestCart->promotions()->create(['discount_id' => Discount::factory()->create()->id, 'source' => PromotionSource::Code->value, 'code' => 'ANY']);
        app()->bind('failing-cart-step', fn (): object => new class
        {
            public function handle(mixed $context, Closure $next): never
            {
                throw new RuntimeException('Tax provider unavailable');
            }
        });
        config()->set('shopper.cart.pipelines.cart', ['failing-cart-step']);
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);

        $session->associate(User::factory()->create());

        Exceptions::assertReported(RuntimeException::class);

        expect(session('shopper_cart'))->toBe($this->guestCart->id);
    });

    it('drops a coupon the user already redeemed once the session cart is theirs', function (bool $ownsCart): void {
        $user = $ownsCart ? $this->user : User::factory()->create();
        $discount = Discount::factory()->create([
            'code' => 'ONCE',
            'is_active' => true,
            'type' => DiscountType::FixedAmount,
            'value' => 100,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'usage_limit_per_user' => true,
            'start_at' => now()->subDay(),
            'end_at' => now()->addMonth(),
        ]);
        $order = Order::query()->create([
            'number' => 'ONCE-1',
            'price_amount' => 900,
            'currency_code' => 'USD',
            'customer_id' => $user->id,
            'discount_id' => $discount->id,
        ]);
        OrderPromotion::query()->create([
            'order_id' => $order->id,
            'discount_id' => $discount->id,
            'code' => 'ONCE',
            'type' => $discount->type->value,
            'value_at_apply' => 100,
            'amount' => 100,
            'currency_code' => 'USD',
        ]);
        $this->cartManager->add($this->guestCart, $this->product);
        $this->cartManager->applyCoupon($this->guestCart, 'ONCE');
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);

        $session->associate($user);

        $cart = $session->current();

        expect($cart->customer_id)->toBe($user->id)
            ->and($cart->id)->toBe($ownsCart ? $this->userCart->id : $this->guestCart->id)
            ->and($cart->promotions()->count())->toBe(0);
    })->with(['merged into the cart the user owns' => true, 'claimed whole' => false]);

    it('attaches the session cart when the user owns no cart', function (): void {
        $freshUser = User::factory()->create();
        $session = resolve(CartSessionManager::class);
        $session->use($this->guestCart);

        $session->associate($freshUser);

        expect($this->guestCart->refresh()->customer_id)->toBe($freshUser->id);
    });
});
