<?php

declare(strict_types=1);

use Laravel\Sanctum\Sanctum;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Zone;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;
use Tests\Api\Stubs\FakePaymentDriver;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

function checkoutCart(Zone $zone, Product $product, int $quantity = 1): Cart
{
    $cart = Cart::query()->create([
        'currency_code' => 'USD',
        'zone_id' => $zone->id,
    ]);

    $cart->lines()->create([
        'purchasable_type' => $product->getMorphClass(),
        'purchasable_id' => $product->id,
        'quantity' => $quantity,
        'unit_price_amount' => 2500,
    ]);

    return $cart;
}

function checkoutAddressPayload(): array
{
    return [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_1' => '1 Main Street',
        'city' => 'New York',
        'postal_code' => '10001',
        'country_code' => 'US',
    ];
}

function readyCart(Cart $cart, CarrierOption $option, PaymentMethod $method): void
{
    $cart->addresses()->create([
        'type' => AddressType::Shipping,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_1' => '1 Main Street',
        'city' => 'New York',
        'postal_code' => '10001',
    ]);

    $cart->update([
        'shipping_option_id' => "main-carrier:{$option->public_id}",
        'shipping_amount' => $option->price,
        'payment_method_id' => $method->id,
    ]);
}

beforeEach(function (): void {
    setupCurrencies();

    $this->country = Country::factory()->create(['cca2' => 'US']);

    $this->zone = Zone::factory()->create(['is_enabled' => true]);
    $this->zone->countries()->attach($this->country);

    $this->carrier = Carrier::factory()->create([
        'name' => 'Main Carrier',
        'slug' => 'main-carrier',
        'is_enabled' => true,
    ]);
    $this->zone->carriers()->attach($this->carrier);

    $this->option = CarrierOption::factory()->create([
        'name' => 'Standard',
        'price' => 700,
        'is_enabled' => true,
        'carrier_id' => $this->carrier->id,
        'zone_id' => $this->zone->id,
    ]);

    $this->paymentMethod = PaymentMethod::factory()->create([
        'title' => 'Cash on delivery',
        'is_enabled' => true,
        'driver' => 'manual',
    ]);
    $this->paymentMethod->zones()->attach($this->zone);

    $this->product = Product::factory()->standard()->publish()->create();

    $this->cart = checkoutCart($this->zone, $this->product);
});

it('sets the shipping and billing addresses of a cart', function (): void {
    $response = $this->postJson("/store/carts/{$this->cart->public_id}/addresses?include=addresses", [
        'shipping_address' => checkoutAddressPayload(),
        'billing_address' => [...checkoutAddressPayload(), 'city' => 'Boston'],
    ])->assertOk();

    $addresses = collect($response->json('included'))->where('type', 'cart-addresses')->values();

    expect($addresses)->toHaveCount(2)
        ->and($addresses->pluck('attributes.city')->all())->toContain('New York', 'Boston')
        ->and($addresses->pluck('attributes.country_code')->unique()->all())->toBe(['US']);
});

it('replaces the address of a type instead of stacking them', function (): void {
    $url = "/store/carts/{$this->cart->public_id}/addresses";

    $this->postJson($url, ['shipping_address' => checkoutAddressPayload()])->assertOk();
    $this->postJson($url, ['shipping_address' => [...checkoutAddressPayload(), 'city' => 'Chicago']])->assertOk();

    expect($this->cart->addresses()->count())->toBe(1)
        ->and($this->cart->addresses()->first()->city)->toBe('Chicago');
});

it('rejects an incomplete checkout address', function (): void {
    $this->postJson("/store/carts/{$this->cart->public_id}/addresses", [
        'shipping_address' => ['first_name' => 'John'],
    ])->assertUnprocessable();
});

it('sets the shipping method and folds its price into the totals', function (): void {
    $this->postJson("/store/carts/{$this->cart->public_id}/shipping-method", [
        'option_id' => "main-carrier:{$this->option->public_id}",
    ])
        ->assertOk()
        ->assertJsonPath('data.attributes.shipping_option_id', "main-carrier:{$this->option->public_id}")
        ->assertJsonPath('data.attributes.shipping_total', 700)
        ->assertJsonPath('data.attributes.total', 3200);

    expect($this->cart->refresh()->shipping_amount)->toBe(700);
});

it('rejects a shipping option the carriers do not quote', function (): void {
    $this->postJson("/store/carts/{$this->cart->public_id}/shipping-method", [
        'option_id' => 'main-carrier:does-not-exist',
    ])->assertUnprocessable();
});

it('lists the payment methods available for the cart zone', function (): void {
    PaymentMethod::factory()->create(['title' => 'Elsewhere', 'is_enabled' => true, 'driver' => 'manual']);

    $disabled = PaymentMethod::factory()->create(['title' => 'Disabled', 'is_enabled' => false, 'driver' => 'manual']);
    $disabled->zones()->attach($this->zone);

    $this->getJson("/store/carts/{$this->cart->public_id}/payment-methods")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.type', 'payment-methods')
        ->assertJsonPath('data.0.id', (string) $this->paymentMethod->public_id)
        ->assertJsonPath('data.0.attributes.title', 'Cash on delivery')
        ->assertJsonPath('data.0.attributes.driver', 'manual');
});

it('sets the payment method of a cart', function (): void {
    $this->postJson("/store/carts/{$this->cart->public_id}/payment-method", [
        'payment_method_id' => (string) $this->paymentMethod->public_id,
    ])->assertOk();

    expect($this->cart->refresh()->payment_method_id)->toBe($this->paymentMethod->id);
});

it('rejects a payment method outside the cart offer', function (): void {
    $elsewhere = PaymentMethod::factory()->create(['title' => 'Elsewhere', 'is_enabled' => true, 'driver' => 'manual']);

    $this->postJson("/store/carts/{$this->cart->public_id}/payment-method", [
        'payment_method_id' => (string) $elsewhere->public_id,
    ])->assertUnprocessable();

    expect($this->cart->refresh()->payment_method_id)->toBeNull();
});

it('opens a payment session through the payment driver', function (): void {
    $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $method = PaymentMethod::factory()->create(['title' => 'Card', 'is_enabled' => true, 'driver' => 'fake']);
    $method->zones()->attach($this->zone);

    $this->cart->update([
        'payment_method_id' => $method->id,
        'shipping_option_id' => "main-carrier:{$this->option->public_id}",
        'shipping_amount' => 700,
    ]);

    $this->postJson("/store/carts/{$this->cart->public_id}/payment-session")
        ->assertCreated()
        ->assertJsonPath('data.type', 'payment-sessions')
        ->assertJsonPath('data.id', 'fake_intent_1')
        ->assertJsonPath('data.attributes.driver', 'fake')
        ->assertJsonPath('data.attributes.client_secret', 'fake_secret_1')
        ->assertJsonPath('data.attributes.amount', 3200)
        ->assertJsonPath('data.attributes.currency_code', 'USD')
        ->assertJsonPath('data.attributes.data.publishable_key', 'pk_fake');

    expect($driver->lastAmount)->toBe(3200)
        ->and($this->cart->refresh()->payment_session['reference'])->toBe('fake_intent_1');
});

it('resumes the payment session while the total is unchanged', function (): void {
    $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $method = PaymentMethod::factory()->create(['title' => 'Card', 'is_enabled' => true, 'driver' => 'fake']);
    $method->zones()->attach($this->zone);

    $this->cart->update(['payment_method_id' => $method->id]);

    $url = "/store/carts/{$this->cart->public_id}/payment-session";

    $this->postJson($url)->assertCreated();
    $this->postJson($url)->assertCreated()->assertJsonPath('data.id', 'fake_intent_1');

    expect($driver->initiations)->toBe(1)
        ->and($driver->retrievals)->toBe(1);
});

it('opens a fresh session when the cart total changed', function (): void {
    $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $method = PaymentMethod::factory()->create(['title' => 'Card', 'is_enabled' => true, 'driver' => 'fake']);
    $method->zones()->attach($this->zone);

    $this->cart->update(['payment_method_id' => $method->id]);

    $url = "/store/carts/{$this->cart->public_id}/payment-session";

    $this->postJson($url)->assertCreated()->assertJsonPath('data.id', 'fake_intent_1');

    $this->cart->lines()->first()->update(['quantity' => 2]);

    $this->postJson($url)->assertCreated()->assertJsonPath('data.id', 'fake_intent_2');

    expect($driver->initiations)->toBe(2);
});

it('requires a payment method before opening a session', function (): void {
    $this->postJson("/store/carts/{$this->cart->public_id}/payment-session")
        ->assertUnprocessable();
});

it('completes the cart into an order', function (): void {
    readyCart($this->cart, $this->option, $this->paymentMethod);

    $response = $this->postJson("/store/carts/{$this->cart->public_id}/complete")
        ->assertCreated()
        ->assertJsonPath('data.type', 'orders')
        ->assertJsonPath('data.attributes.status', 'new')
        ->assertJsonPath('data.attributes.payment_status', 'pending')
        ->assertJsonPath('data.attributes.shipping_amount', 700)
        ->assertJsonPath('data.attributes.price_amount', 3200)
        ->assertJsonPath('data.attributes.total', 2500);

    $order = Order::query()->where('public_id', $response->json('data.id'))->first();
    $cart = $this->cart->refresh();

    expect($order)->not->toBeNull()
        ->and($order->payment_method_id)->toBe($this->paymentMethod->id)
        ->and($order->shipping_option_id)->toBe($this->option->id)
        ->and($order->items()->count())->toBe(1)
        ->and($cart->isCompleted())->toBeTrue()
        ->and($cart->order_id)->toBe($order->id);
});

it('answers the same order when the cart is completed twice', function (): void {
    readyCart($this->cart, $this->option, $this->paymentMethod);

    $url = "/store/carts/{$this->cart->public_id}/complete";

    $orderId = $this->postJson($url)->assertCreated()->json('data.id');

    $this->postJson($url)
        ->assertOk()
        ->assertJsonPath('data.id', $orderId);

    expect(Order::query()->count())->toBe(1);
});

it('rejects completion when the shipping price increased since selection', function (): void {
    readyCart($this->cart, $this->option, $this->paymentMethod);

    $this->option->update(['price' => 900]);

    $this->postJson("/store/carts/{$this->cart->public_id}/complete")
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/shipping_method');

    expect($this->cart->refresh()->isCompleted())->toBeFalse();
});

it('absorbs a shipping price drop at completion', function (): void {
    readyCart($this->cart, $this->option, $this->paymentMethod);

    $this->option->update(['price' => 500]);

    $this->postJson("/store/carts/{$this->cart->public_id}/complete")
        ->assertCreated()
        ->assertJsonPath('data.attributes.shipping_amount', 500)
        ->assertJsonPath('data.attributes.price_amount', 3000);
});

it('requires a shipping method to complete a shippable cart', function (): void {
    $this->cart->update(['payment_method_id' => $this->paymentMethod->id]);

    $this->postJson("/store/carts/{$this->cart->public_id}/complete")
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/shipping_method');
});

it('completes a cart of virtual products without any shipping method', function (): void {
    $virtual = Product::factory()->virtual()->publish()->create();
    $cart = checkoutCart($this->zone, $virtual);
    $cart->update(['payment_method_id' => $this->paymentMethod->id]);

    $this->postJson("/store/carts/{$cart->public_id}/complete")
        ->assertCreated()
        ->assertJsonPath('data.attributes.shipping_amount', null)
        ->assertJsonPath('data.attributes.price_amount', 2500);
});

it('requires a payment method to complete the cart', function (): void {
    $this->postJson("/store/carts/{$this->cart->public_id}/complete")
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/payment_method');
});

it('rejects the completion of an empty cart', function (): void {
    $cart = Cart::query()->create(['currency_code' => 'USD', 'zone_id' => $this->zone->id]);

    $this->postJson("/store/carts/{$cart->public_id}/complete")
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/cart');
});

it('records the initiated payment on the order', function (): void {
    $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $method = PaymentMethod::factory()->create(['title' => 'Card', 'is_enabled' => true, 'driver' => 'fake']);
    $method->zones()->attach($this->zone);

    readyCart($this->cart, $this->option, $method);

    $this->postJson("/store/carts/{$this->cart->public_id}/payment-session")->assertCreated();

    $orderId = $this->postJson("/store/carts/{$this->cart->public_id}/complete")
        ->assertCreated()
        ->json('data.id');

    $order = Order::query()->where('public_id', $orderId)->first();
    $transaction = PaymentTransaction::query()->where('order_id', $order->id)->first();

    expect($transaction)->not->toBeNull()
        ->and($transaction->type)->toBe(TransactionType::Initiate)
        ->and($transaction->reference)->toBe('fake_intent_1')
        ->and($transaction->amount)->toBe(3200);
});

it('rejects completion when the payment session no longer matches the total', function (): void {
    $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $method = PaymentMethod::factory()->create(['title' => 'Card', 'is_enabled' => true, 'driver' => 'fake']);
    $method->zones()->attach($this->zone);

    readyCart($this->cart, $this->option, $method);

    $this->postJson("/store/carts/{$this->cart->public_id}/payment-session")->assertCreated();

    $this->cart->lines()->first()->update(['quantity' => 2]);

    $this->postJson("/store/carts/{$this->cart->public_id}/complete")
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/payment_session');
});

it('rejects address changes on a completed cart', function (): void {
    readyCart($this->cart, $this->option, $this->paymentMethod);
    $this->postJson("/store/carts/{$this->cart->public_id}/complete")->assertCreated();

    $this->postJson("/store/carts/{$this->cart->public_id}/addresses", [
        'shipping_address' => checkoutAddressPayload(),
    ])->assertConflict();
});

it('retrieves a guest order by its public id', function (): void {
    readyCart($this->cart, $this->option, $this->paymentMethod);

    $orderId = $this->postJson("/store/carts/{$this->cart->public_id}/complete")->json('data.id');

    $this->getJson("/store/orders/{$orderId}?include=items")
        ->assertOk()
        ->assertJsonPath('data.id', $orderId)
        ->assertJsonPath('data.attributes.price_amount', 3200)
        ->assertJsonPath('data.attributes.total', 2500)
        ->assertJsonPath('included.0.type', 'order-items');
});

it('hides a customer order from guests and other customers', function (): void {
    $customer = User::factory()->create();
    $this->cart->update(['customer_id' => $customer->id]);
    readyCart($this->cart, $this->option, $this->paymentMethod);

    Sanctum::actingAs($customer, ['store']);
    $orderId = $this->postJson("/store/carts/{$this->cart->public_id}/complete")->json('data.id');

    $this->app->make('auth')->forgetGuards();

    $this->getJson("/store/orders/{$orderId}")->assertNotFound();

    $other = User::factory()->create();
    Sanctum::actingAs($other, ['store']);

    $this->getJson("/store/orders/{$orderId}")->assertNotFound();

    Sanctum::actingAs($customer, ['store']);

    $this->getJson("/store/orders/{$orderId}")->assertOk();
});

it('returns 404 for an unknown order', function (): void {
    $this->getJson('/store/orders/01JUNKNOWNORDERID000000000')->assertNotFound();
});

it('keeps personal data out of the guest order lookup', function (): void {
    readyCart($this->cart, $this->option, $this->paymentMethod);

    $orderId = $this->postJson("/store/carts/{$this->cart->public_id}/complete")->json('data.id');

    $this->getJson("/store/orders/{$orderId}?include=shipping_address")
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/include');

    $this->getJson("/store/orders/{$orderId}?include=items")->assertOk();
});

it('cancels the replaced payment session at the provider', function (): void {
    $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $method = PaymentMethod::factory()->create(['title' => 'Card', 'is_enabled' => true, 'driver' => 'fake']);
    $method->zones()->attach($this->zone);

    $this->cart->update(['payment_method_id' => $method->id]);

    $url = "/store/carts/{$this->cart->public_id}/payment-session";

    $this->postJson($url)->assertCreated();

    $this->cart->lines()->first()->update(['quantity' => 2]);

    $this->postJson($url)->assertCreated()->assertJsonPath('data.id', 'fake_intent_2');

    expect($driver->cancellations)->toBe(1)
        ->and($driver->lastCancelledReference)->toBe('fake_intent_1');
});

it('never reuses an idempotency key when the cart total round-trips', function (): void {
    $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $method = PaymentMethod::factory()->create(['title' => 'Card', 'is_enabled' => true, 'driver' => 'fake']);
    $method->zones()->attach($this->zone);

    $this->cart->update(['payment_method_id' => $method->id]);

    $url = "/store/carts/{$this->cart->public_id}/payment-session";

    $this->postJson($url)->assertCreated();

    $this->cart->lines()->first()->update(['quantity' => 2]);
    $this->postJson($url)->assertCreated();

    $this->cart->lines()->first()->update(['quantity' => 1]);
    $this->postJson($url)->assertCreated();

    expect($driver->initiations)->toBe(3)
        ->and($driver->idempotencyKeys)->toHaveCount(3)
        ->and(array_unique($driver->idempotencyKeys))->toHaveCount(3);
});

it('answers the existing order when a concurrent completion already closed the cart', function (): void {
    readyCart($this->cart, $this->option, $this->paymentMethod);

    $orderId = $this->postJson("/store/carts/{$this->cart->public_id}/complete")
        ->assertCreated()
        ->json('data.id');

    // A request that read the cart before the winner committed still holds a
    // stale, not-completed view of it.
    $stale = Cart::query()->find($this->cart->id);
    $stale->setAttribute('completed_at', null);

    $order = app(Shopper\Api\Actions\CompleteCartAction::class)->execute($stale);

    expect($order->public_id)->toBe($orderId)
        ->and(Order::query()->count())->toBe(1)
        ->and(PaymentTransaction::query()->count())->toBe(0);
});

it('creates an order with price_amount = discounted items + shipping', function (): void {
    Discount::factory()->create([
        'code' => 'SAVE20',
        'is_active' => true,
        'type' => DiscountType::Percentage,
        'value' => 20,
        'apply_to' => DiscountApplyTo::Order,
        'eligibility' => DiscountEligibility::Everyone,
        'min_required' => DiscountRequirement::None,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
    ]);

    readyCart($this->cart, $this->option, $this->paymentMethod);
    $this->cart->update(['coupon_code' => 'SAVE20']);

    // items 2500 - 20% discount (500) + shipping 700 = 2700
    $this->postJson("/store/carts/{$this->cart->public_id}/complete")
        ->assertCreated()
        ->assertJsonPath('data.attributes.price_amount', 2700)
        ->assertJsonPath('data.attributes.shipping_amount', 700)
        ->assertJsonPath('data.attributes.total', 2000);
});

it('requires a shipping method when the cart mixes virtual and physical products', function (): void {
    $virtual = Product::factory()->virtual()->publish()->create();
    $cart = checkoutCart($this->zone, $virtual);

    $cart->lines()->create([
        'purchasable_type' => $this->product->getMorphClass(),
        'purchasable_id' => $this->product->id,
        'quantity' => 1,
        'unit_price_amount' => 2500,
    ]);
    $cart->update(['payment_method_id' => $this->paymentMethod->id]);

    $this->postJson("/store/carts/{$cart->public_id}/complete")
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/shipping_method');
});

it('rejects every checkout mutation on a completed cart', function (): void {
    readyCart($this->cart, $this->option, $this->paymentMethod);
    $this->postJson("/store/carts/{$this->cart->public_id}/complete")->assertCreated();

    $base = "/store/carts/{$this->cart->public_id}";

    $this->postJson("{$base}/shipping-method", [
        'option_id' => "main-carrier:{$this->option->public_id}",
    ])->assertConflict();

    $this->postJson("{$base}/payment-method", [
        'payment_method_id' => (string) $this->paymentMethod->public_id,
    ])->assertConflict();

    $this->postJson("{$base}/payment-session")->assertConflict();
});

it('opens a fresh session when the provider reports the stored one unusable', function (): void {
    $driver = new FakePaymentDriver;
    $driver->failRetrieve = true;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $method = PaymentMethod::factory()->create(['title' => 'Card', 'is_enabled' => true, 'driver' => 'fake']);
    $method->zones()->attach($this->zone);

    $this->cart->update(['payment_method_id' => $method->id]);

    $url = "/store/carts/{$this->cart->public_id}/payment-session";

    $this->postJson($url)->assertCreated()->assertJsonPath('data.id', 'fake_intent_1');
    $this->postJson($url)->assertCreated()->assertJsonPath('data.id', 'fake_intent_2');

    expect($driver->retrievals)->toBe(1)
        ->and($driver->initiations)->toBe(2);
});
