<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Sleep;
use Laravel\Sanctum\Sanctum;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Contracts\PriceResolver;
use Shopper\Core\Contracts\QuantityRuleResolver;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderPromotion;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Models\Zone;
use Shopper\Core\Pricing\PricingContext;
use Shopper\Core\Pricing\QuantityRule;
use Shopper\Payment\Facades\Payment;
use Tests\Api\Stubs\FakePaymentDriver;
use Tests\Cart\Stubs\TieredPriceResolver;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->driver = $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $this->resolver = new TieredPriceResolver;
    $this->app->instance(PriceResolver::class, $this->resolver);
    $this->cartManager = resolve(CartManager::class);

    $this->product = Product::factory()->standard()->publish()->create();
    $this->product->prices()->create([
        'amount' => 1000,
        'currency_id' => Currency::query()->where('code', 'USD')->value('id'),
    ]);
    $this->product->mutateStock(Inventory::factory()->create()->id, 50);
});

it('stores the resolved channel on a new cart', function (): void {
    $channel = Channel::factory()->create(['slug' => 'wholesale', 'is_enabled' => true, 'is_default' => false]);

    $cartId = $this->postJson('/store/carts', [], ['X-Shopper-Channel' => 'wholesale'])
        ->assertCreated()
        ->json('data.id');

    expect(Cart::query()->where('public_id', $cartId)->value('channel_id'))->toBe($channel->id);
});

it('moves a cart to another zone and reprices its lines for it', function (): void {
    $zone = Zone::factory()->create(['code' => 'fr', 'is_enabled' => true]);
    $channel = Channel::factory()->create();
    $cart = Cart::factory()->create(['currency_code' => 'USD', 'channel_id' => $channel->id]);
    $this->cartManager->add($cart, $this->product);
    $cart->update(['shipping_option_id' => 'carrier:standard', 'shipping_amount' => 500, 'payment_method_id' => PaymentMethod::factory()->create()->id]);
    $this->resolver->base = 800;

    $this->patchJson("/store/carts/{$cart->public_id}", ['zone_code' => 'fr'])->assertOk();

    expect($cart->refresh())
        ->zone_id->toBe($zone->id)
        ->channel_id->toBe($channel->id)
        ->currency_code->toBe('USD')
        ->shipping_option_id->toBeNull()
        ->payment_method_id->toBeNull()
        ->and($cart->lines()->value('unit_price_amount'))->toBe(800)
        ->and($this->resolver->lastContext)->zoneId->toBe($zone->id)->channelId->toBe($channel->id);
});

it('drops a coupon bound to the zone a cart leaves', function (): void {
    $from = Zone::factory()->create(['code' => 'be', 'is_enabled' => true]);
    Zone::factory()->create(['code' => 'fr', 'is_enabled' => true]);
    $discount = Discount::factory()->create([
        'code' => 'BE5',
        'is_active' => true,
        'type' => DiscountType::FixedAmount,
        'value' => 500,
        'apply_to' => DiscountApplyTo::Order,
        'eligibility' => DiscountEligibility::Everyone,
        'min_required' => DiscountRequirement::None,
        'zone_id' => $from->id,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
    ]);
    $cart = Cart::factory()->create(['currency_code' => 'USD', 'zone_id' => $from->id]);
    $this->cartManager->add($cart, $this->product);
    $cart->promotions()->create(['discount_id' => $discount->id, 'source' => 'code', 'code' => 'BE5']);

    $this->patchJson("/store/carts/{$cart->public_id}", ['zone_code' => 'fr'])->assertOk();

    expect($cart->promotions()->exists())->toBeFalse();
});

it('rejects a zone the shop has disabled', function (): void {
    Zone::factory()->create(['code' => 'be', 'is_enabled' => false]);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);

    $this->patchJson("/store/carts/{$cart->public_id}", ['zone_code' => 'be'])
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/zone_code');

    expect($cart->refresh()->zone_id)->toBeNull();
});

it('keeps a cart in its zone without waiting for its payment session to open', function (): void {
    Sleep::fake(syncWithCarbon: true);

    $zone = Zone::factory()->create(['code' => 'fr', 'is_enabled' => true]);
    $cart = Cart::factory()->create(['currency_code' => 'USD', 'zone_id' => $zone->id]);
    Cache::lock("cart:payment-session:{$cart->public_id}", 30)->get();

    $this->patchJson("/store/carts/{$cart->public_id}", ['zone_code' => 'fr', 'email' => 'john@example.com'])->assertOk();

    expect($cart->refresh()->email)->toBe('john@example.com');
});

it('leaves the payment session alone when another request already moved the cart to the zone', function (): void {
    $zone = Zone::factory()->create(['code' => 'fr', 'is_enabled' => true]);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->cartManager->add($cart, $this->product);
    $this->cartManager->setPaymentSession($cart, ['driver' => 'fake', 'reference' => 'fake_intent_old', 'amount' => 1000]);
    $cartId = $cart->id;
    $moved = false;

    Cart::retrieved(function (Cart $read) use ($cartId, $zone, &$moved): void {
        if ($read->id === $cartId && ! $moved) {
            $moved = true;
            DB::table($read->getTable())->where('id', $cartId)->update([
                'zone_id' => $zone->id,
                'payment_session' => json_encode(['driver' => 'fake', 'reference' => 'fake_intent_new', 'amount' => 1000]),
            ]);
        }
    });

    $this->patchJson("/store/carts/{$cart->public_id}", ['zone_code' => 'fr'])->assertOk();

    expect($cart->refresh()->payment_session['reference'])->toBe('fake_intent_new')
        ->and($this->driver->lastCancelledReference)->toBeNull();
});

it('reports the lines repriced by a transfer', function (): void {
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $line = $this->cartManager->add($cart, $this->product, quantity: 2);

    Sanctum::actingAs(User::factory()->create(), ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer?include=lines")
        ->assertOk()
        ->assertJsonPath('meta.price_changes', [
            ['line' => $line->public_id, 'from' => 1000, 'to' => 900],
        ]);
});

it('reports the customer lines repriced by the quantity a transfer brings', function (): void {
    $this->resolver->tierOnProduct = true;
    $currency = Currency::query()->where('code', 'USD')->value('id');
    [$small, $large] = collect(['S', 'L'])->map(function () use ($currency): ProductVariant {
        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        $variant->prices()->create(['amount' => 1000, 'currency_id' => $currency]);
        $variant->mutateStock(Inventory::query()->value('id'), 50);

        return $variant;
    })->all();

    $customer = User::factory()->create();
    $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    $owned = $this->cartManager->add($customerCart, $small, quantity: 6);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $moved = $this->cartManager->add($cart, $large, quantity: 4);

    Sanctum::actingAs($customer, ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer")
        ->assertOk()
        ->assertJsonPath('meta.price_changes', [
            ['line' => $owned->public_id, 'from' => 900, 'to' => 700],
            ['line' => $moved->public_id, 'from' => 1000, 'to' => 700],
        ]);
});

it('releases the unpaid payment session of a guest cart it claims on transfer', function (): void {
    $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);
    $customer = User::factory()->create();
    Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->cartManager->add($cart, $this->product, quantity: 2);
    $this->cartManager->setPaymentSession($cart, ['driver' => 'fake', 'reference' => 'fake_intent_9', 'amount' => 2000]);

    Sanctum::actingAs($customer, ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer")
        ->assertOk()
        ->assertJsonPath('data.id', $cart->public_id);

    expect($cart->refresh())
        ->customer_id->toBe($customer->id)
        ->payment_session->toBeNull()
        ->and($driver->lastCancelledReference)->toBe('fake_intent_9');
});

it('never merges a guest cart into a customer cart with an open payment session', function (): void {
    $customer = User::factory()->create();
    $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    $owned = $this->cartManager->add($customerCart, $this->product);
    $this->cartManager->setPaymentSession($customerCart, ['driver' => 'fake', 'reference' => 'fake_intent_a', 'amount' => 900]);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->cartManager->add($cart, $this->product);

    Sanctum::actingAs($customer, ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer")
        ->assertOk()
        ->assertJsonPath('data.id', $cart->public_id);

    expect($customerCart->refresh()->payment_session['reference'])->toBe('fake_intent_a')
        ->and($owned->refresh()->quantity)->toBe(1)
        ->and($cart->refresh()->customer_id)->toBe($customer->id);
});

it('answers 409 on transfer while a payment session opens on either cart', function (bool $lockGuest): void {
    Sleep::fake(syncWithCarbon: true);

    $customer = User::factory()->create();
    $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    $this->cartManager->add($customerCart, $this->product);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $line = $this->cartManager->add($cart, $this->product);

    Cache::lock('cart:payment-session:'.($lockGuest ? $cart : $customerCart)->public_id, 30)->get();

    Sanctum::actingAs($customer, ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer")
        ->assertConflict()
        ->assertJsonPath('errors.0.code', 'payment_session_in_progress')
        ->assertHeader('Retry-After');

    expect($cart->refresh()->customer_id)->toBeNull()
        ->and($line->refresh()->cart_id)->toBe($cart->id);
})->with(['guest cart' => true, 'customer cart' => false]);

it('confirms a cart the customer already owns without waiting for its payment session to open', function (): void {
    Sleep::fake(syncWithCarbon: true);

    $customer = User::factory()->create();
    $cart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    Cache::lock("cart:payment-session:{$cart->public_id}", 30)->get();

    Sanctum::actingAs($customer, ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer")
        ->assertOk()
        ->assertJsonPath('data.id', $cart->public_id);
});

it('claims a guest cart whose payment session opened after the transfer read it', function (): void {
    $customer = User::factory()->create();
    $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $line = $this->cartManager->add($cart, $this->product);
    $cartId = $cart->id;
    $opened = false;

    Cart::retrieved(function (Cart $read) use ($cartId, &$opened): void {
        if ($read->id === $cartId && ! $opened) {
            $opened = true;
            DB::table($read->getTable())->where('id', $cartId)->update([
                'payment_session' => json_encode(['driver' => 'fake', 'reference' => 'fake_intent_7', 'amount' => 1000]),
            ]);
        }
    });

    Sanctum::actingAs($customer, ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer")
        ->assertOk()
        ->assertJsonPath('data.id', $cart->public_id);

    expect($cart->refresh()->payment_session)->toBeNull()
        ->and($this->driver->lastCancelledReference)->toBe('fake_intent_7')
        ->and($line->refresh()->cart_id)->toBe($cart->id)
        ->and($customerCart->lines()->count())->toBe(0);
});

it('claims the guest cart instead of merging into a customer cart that changed after the transfer read it', function (string $column, mixed $value): void {
    $customer = User::factory()->create();
    $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    $owned = $this->cartManager->add($customerCart, $this->product);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $line = $this->cartManager->add($cart, $this->product, quantity: 2);
    $customerCartId = $customerCart->id;
    $changed = false;

    Cart::retrieved(function (Cart $read) use ($customerCartId, &$changed, $column, $value): void {
        if ($read->id === $customerCartId && ! $changed) {
            $changed = true;
            DB::table($read->getTable())->where('id', $customerCartId)->update([$column => $value]);
        }
    });

    Sanctum::actingAs($customer, ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer")
        ->assertOk()
        ->assertJsonPath('data.id', $cart->public_id);

    expect($cart->refresh()->customer_id)->toBe($customer->id)
        ->and($line->refresh()->cart_id)->toBe($cart->id)
        ->and($owned->refresh()->quantity)->toBe(1);
})->with([
    'completed' => fn (): array => ['completed_at', now()],
    'payment session opened' => fn (): array => ['payment_session', json_encode(['driver' => 'fake', 'reference' => 'fake_intent_9', 'amount' => 900])],
]);

it('logs the customer in and answers the guest cart whose payment session is opening', function (): void {
    Sleep::fake(syncWithCarbon: true);

    $customer = User::factory()->create(['email' => 'john@example.com', 'password' => Hash::make('correct-password')]);
    $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $line = $this->cartManager->add($cart, $this->product);

    Cache::lock("cart:payment-session:{$cart->public_id}", 30)->get();

    $this->postJson('/store/auth/login', [
        'email' => 'john@example.com',
        'password' => 'correct-password',
        'cart_id' => $cart->public_id,
    ])->assertOk()->assertJsonPath('meta.cart_id', $cart->public_id);

    expect($cart->refresh()->customer_id)->toBeNull()
        ->and($line->refresh()->cart_id)->toBe($cart->id)
        ->and($customerCart->lines()->count())->toBe(0);
});

it('answers 409 on a currency change while the payment session opens', function (): void {
    Sleep::fake(syncWithCarbon: true);

    setupCurrencies(['USD', 'EUR']);
    Currency::query()->where('code', 'EUR')->update(['is_enabled' => true]);
    $this->product->prices()->create([
        'amount' => 900,
        'currency_id' => Currency::query()->where('code', 'EUR')->value('id'),
    ]);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->cartManager->add($cart, $this->product);

    Cache::lock("cart:payment-session:{$cart->public_id}", 30)->get();

    $this->patchJson("/store/carts/{$cart->public_id}", ['currency_code' => 'EUR'])
        ->assertConflict()
        ->assertJsonPath('errors.0.code', 'payment_session_in_progress');

    expect($cart->refresh()->currency_code)->toBe('USD');
});

it('revalidates the coupon of a guest cart on transfer unless its payment is collected', function (string $status): void {
    $driver = new FakePaymentDriver;
    $driver->retrievedStatus = $status;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);
    $customer = User::factory()->create();
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
        'customer_id' => $customer->id,
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
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->cartManager->add($cart, $this->product);
    $cart->promotions()->create(['discount_id' => $discount->id, 'source' => 'code', 'code' => 'ONCE']);
    $this->cartManager->setPaymentSession($cart, ['driver' => 'fake', 'reference' => 'fake_intent_c', 'amount' => 900]);

    Sanctum::actingAs($customer, ['store']);

    $response = $this->postJson("/store/carts/{$cart->public_id}/transfer");

    if ($status === 'captured') {
        $response->assertConflict()->assertJsonPath('errors.0.code', 'payment_session_collected');

        expect($cart->promotions()->count())->toBe(1)
            ->and($cart->refresh()->customer_id)->toBeNull()
            ->and($cart->payment_session['reference'])->toBe('fake_intent_c');

        return;
    }

    $response->assertOk();

    expect($cart->promotions()->count())->toBe(0)
        ->and($cart->refresh()->payment_session)->toBeNull();
})->with(['unpaid' => 'pending', 'paid' => 'captured']);

it('refuses a currency change while the payment session may already be paid', function (?string $status): void {
    setupCurrencies(['USD', 'EUR']);
    Currency::query()->where('code', 'EUR')->update(['is_enabled' => true]);
    $this->product->prices()->create([
        'amount' => 900,
        'currency_id' => Currency::query()->where('code', 'EUR')->value('id'),
    ]);
    $driver = new FakePaymentDriver;
    $driver->retrieval = $status !== null;
    $driver->retrievedStatus = $status ?? 'pending';
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->cartManager->add($cart, $this->product);
    $this->cartManager->setPaymentSession($cart, ['driver' => 'fake', 'reference' => 'fake_intent_p', 'amount' => 1000]);

    $this->patchJson("/store/carts/{$cart->public_id}", ['currency_code' => 'EUR'])
        ->assertConflict()
        ->assertJsonPath('errors.0.code', 'payment_session_collected');

    expect($cart->refresh())
        ->currency_code->toBe('USD')
        ->and($cart->payment_session['reference'])->toBe('fake_intent_p')
        ->and($driver->lastCancelledReference)->toBeNull();
})->with(['captured' => 'captured', 'driver without retrieval' => null]);

it('keeps the session and refuses the currency change when the intent is paid while it is released', function (): void {
    setupCurrencies(['USD', 'EUR']);
    Currency::query()->where('code', 'EUR')->update(['is_enabled' => true]);
    $this->product->prices()->create([
        'amount' => 900,
        'currency_id' => Currency::query()->where('code', 'EUR')->value('id'),
    ]);
    $driver = new FakePaymentDriver;
    $driver->retrievedStatuses = ['pending', 'captured'];
    $driver->throwOnCancel = true;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->cartManager->add($cart, $this->product);
    $this->cartManager->setPaymentSession($cart, ['driver' => 'fake', 'reference' => 'fake_intent_r', 'amount' => 1000]);

    $this->patchJson("/store/carts/{$cart->public_id}", ['currency_code' => 'EUR'])
        ->assertConflict()
        ->assertJsonPath('errors.0.code', 'payment_session_collected');

    expect($cart->refresh())
        ->currency_code->toBe('USD')
        ->and($cart->payment_session['reference'])->toBe('fake_intent_r');
});

it('changes the currency and cancels the unpaid intent of the payment session', function (bool $unreachable): void {
    setupCurrencies(['USD', 'EUR']);
    Currency::query()->where('code', 'EUR')->update(['is_enabled' => true]);
    $this->product->prices()->create([
        'amount' => 900,
        'currency_id' => Currency::query()->where('code', 'EUR')->value('id'),
    ]);
    $driver = new FakePaymentDriver;
    $driver->retrievedStatus = 'pending';
    $driver->throwOnRetrieve = $unreachable;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->cartManager->add($cart, $this->product);
    $this->cartManager->setPaymentSession($cart, ['driver' => 'fake', 'reference' => 'fake_intent_q', 'amount' => 1000]);

    $response = $this->patchJson("/store/carts/{$cart->public_id}", ['currency_code' => 'EUR']);

    if ($unreachable) {
        $response->assertStatus(503)->assertJsonPath('errors.0.code', 'payment_provider_unavailable');

        expect($cart->refresh()->currency_code)->toBe('USD')
            ->and($cart->payment_session['reference'])->toBe('fake_intent_q')
            ->and($driver->lastCancelledReference)->toBeNull();

        return;
    }

    $response->assertOk();

    expect($cart->refresh()->currency_code)->toBe('EUR')
        ->and($cart->payment_session)->toBeNull()
        ->and($driver->lastCancelledReference)->toBe('fake_intent_q');
})->with(['pending' => false, 'provider unreachable' => true]);

it('leaves the payment session alone when another request already applied the currency', function (): void {
    setupCurrencies(['USD', 'EUR']);
    Currency::query()->where('code', 'EUR')->update(['is_enabled' => true]);
    $this->product->prices()->create([
        'amount' => 900,
        'currency_id' => Currency::query()->where('code', 'EUR')->value('id'),
    ]);
    $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->cartManager->add($cart, $this->product);
    $cartId = $cart->id;
    $applied = false;

    Cart::retrieved(function (Cart $read) use ($cartId, &$applied): void {
        if ($read->id === $cartId && ! $applied) {
            $applied = true;
            DB::table($read->getTable())->where('id', $cartId)->update([
                'currency_code' => 'EUR',
                'payment_session' => json_encode(['driver' => 'fake', 'reference' => 'fake_intent_new', 'amount' => 900]),
            ]);
        }
    });

    $this->patchJson("/store/carts/{$cart->public_id}", ['currency_code' => 'EUR'])->assertOk();

    expect($cart->refresh()->payment_session['reference'])->toBe('fake_intent_new')
        ->and($driver->lastCancelledReference)->toBeNull();
});

it('does not report a currency conversion as a price change', function (): void {
    $customer = User::factory()->create();
    Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    $cart = Cart::factory()->create(['currency_code' => 'EUR']);
    $this->cartManager->add($cart, $this->product);

    Sanctum::actingAs($customer, ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer")
        ->assertOk()
        ->assertJsonPath('meta.price_changes', []);
});

it('sells a product the resolver prices without a catalog price, in any currency it prices', function (): void {
    setupCurrencies(['USD', 'EUR']);
    Currency::query()->where('code', 'EUR')->update(['is_enabled' => true]);
    $product = Product::factory()->standard()->publish()->create();
    $product->mutateStock(Inventory::factory()->create()->id, 5);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);

    $this->postJson("/store/carts/{$cart->public_id}/lines", [
        'purchasable_type' => 'product',
        'purchasable_id' => $product->public_id,
    ])->assertOk();

    $this->patchJson("/store/carts/{$cart->public_id}", ['currency_code' => 'EUR'])->assertOk();

    expect($cart->refresh()->currency_code)->toBe('EUR')
        ->and($cart->lines()->sole()->unit_price_amount)->toBe(1000);
});

it('claims the guest cart as it is when a manually priced line blocks the merge', function (): void {
    $customer = User::factory()->create();
    $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    $locked = $this->cartManager->add($customerCart, $this->product, quantity: 10);
    $this->cartManager->setLinePrice($customerCart, $locked->id, 450);
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->cartManager->add($cart, $this->product, quantity: 2);

    Sanctum::actingAs($customer, ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer")
        ->assertOk()
        ->assertJsonPath('data.id', $cart->public_id);

    expect($cart->refresh()->customer_id)->toBe($customer->id)
        ->and($locked->refresh()->quantity)->toBe(10);
});

it('reports no price change when a transfer keeps every price', function (): void {
    $customer = User::factory()->create();
    $cart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $customer->id]);
    $this->cartManager->add($cart, $this->product);

    Sanctum::actingAs($customer, ['store']);

    $this->postJson("/store/carts/{$cart->public_id}/transfer")
        ->assertOk()
        ->assertJsonPath('meta.price_changes', []);
});

it('exposes a manually priced line and refuses to change its quantity', function (): void {
    $cart = Cart::factory()->create(['currency_code' => 'USD']);
    $line = $this->cartManager->add($cart, $this->product, quantity: 10);
    $this->cartManager->setLinePrice($cart, $line->id, 450);

    $this->getJson("/store/carts/{$cart->public_id}?include=lines")
        ->assertOk()
        ->assertJsonPath('included.0.attributes.is_custom_price', true)
        ->assertJsonPath('included.0.attributes.unit_price_amount', 450);

    $this->patchJson("/store/carts/{$cart->public_id}/lines/{$line->public_id}", ['quantity' => 1000])
        ->assertStatus(409)
        ->assertJsonPath('errors.0.code', 'cart_line_locked');

    expect($line->refresh()->quantity)->toBe(10);
});

it('rejects a quantity outside the resolved rule with the rule in the error meta', function (): void {
    $this->app->instance(QuantityRuleResolver::class, new class implements QuantityRuleResolver
    {
        public function resolve(Priceable&Model $purchasable, PricingContext $context): QuantityRule
        {
            return new QuantityRule(minimum: 12, increment: 6);
        }
    });

    $cartId = $this->postJson('/store/carts')->json('data.id');

    $this->postJson("/store/carts/{$cartId}/lines", [
        'purchasable_type' => 'product',
        'purchasable_id' => $this->product->public_id,
        'quantity' => 5,
    ])
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.code', 'quantity_rule_violated')
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/quantity')
        ->assertJsonPath('errors.0.meta', [
            'purchasable_id' => $this->product->public_id,
            'quantity' => 5,
            'minimum' => 12,
            'maximum' => null,
            'increment' => 6,
        ]);
});
