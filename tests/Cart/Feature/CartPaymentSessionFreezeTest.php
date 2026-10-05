<?php

declare(strict_types=1);

use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Sleep;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\PaymentSessionCollectedException;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Contracts\PaymentSessionGateway;
use Shopper\Core\Contracts\PriceResolver;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Zone;
use Tests\Cart\Stubs\EnvelopedSessionCart;
use Tests\Cart\Stubs\FakePaymentSessionGateway;
use Tests\Cart\Stubs\TieredPriceResolver;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies(['USD', 'EUR']);
    Currency::query()->where('code', 'EUR')->update(['is_enabled' => true]);

    $this->gateway = new FakePaymentSessionGateway;
    $this->app->instance(PaymentSessionGateway::class, $this->gateway);
    $this->resolver = new TieredPriceResolver;
    $this->app->instance(PriceResolver::class, $this->resolver);
    $this->cartManager = resolve(CartManager::class);

    $this->product = Product::factory()->standard()->create();
    $this->other = Product::factory()->standard()->create();
    $inventory = Inventory::factory()->create();
    $this->product->mutateStock($inventory->id, 100);
    $this->other->mutateStock($inventory->id, 100);
    $this->cart = Cart::factory()->create(['currency_code' => 'USD']);
    $this->line = $this->cartManager->add($this->cart, $this->product);
    $this->address = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_1' => '123 Main St',
        'city' => 'New York',
        'postal_code' => '10001',
        'country_id' => (Country::query()->where('cca2', 'US')->first() ?? Country::factory()->create(['cca2' => 'US']))->id,
    ];
    $this->session = ['driver' => 'fake', 'reference' => 'pi_1', 'amount' => 1000, 'currency' => 'USD'];
});

it('refuses to change the total of a cart whose payment is collected', function (Closure $mutation): void {
    $this->gateway->paid = true;
    $this->cartManager->setPaymentSession($this->cart, $this->session);
    $lines = $this->cart->lines()->pluck('quantity', 'id')->all();

    expect(fn (): mixed => $mutation->call($this))->toThrow(PaymentSessionCollectedException::class);

    expect($this->cart->refresh())
        ->payment_session->toEqual($this->session)
        ->currency_code->toBe('USD')
        ->and($this->cart->lines()->pluck('quantity', 'id')->all())->toBe($lines)
        ->and($this->line->refresh()->unit_price_amount)->toBe(1000);
})->with([
    'add a line' => [fn () => $this->cartManager->add($this->cart, $this->other)],
    'change a quantity' => [fn () => $this->cartManager->update($this->cart, $this->line->id, ['quantity' => 3])],
    'remove a line' => [fn () => $this->cartManager->remove($this->cart, $this->line->id)],
    'clear the cart' => [fn () => $this->cartManager->clear($this->cart)],
    'set a shipping address' => [fn () => $this->cartManager->addAddress($this->cart, AddressType::Shipping, $this->address)],
    'change the shipping address' => [function (): void {
        $this->cart->addresses()->create([...$this->address, 'type' => AddressType::Shipping]);
        $this->cartManager->addAddress($this->cart, AddressType::Shipping, [...$this->address, 'postal_code' => '94105']);
    }],
    'set a shipping method' => [fn () => $this->cartManager->setShippingMethod($this->cart, 'fedex:ground', 500)],
    'switch the payment method' => [fn () => $this->cartManager->setPaymentMethod($this->cart, PaymentMethod::factory()->create()->id)],
    'change the currency' => [fn () => $this->cartManager->changeCurrency($this->cart, 'EUR')],
    'change the zone' => [fn () => $this->cartManager->changeContext($this->cart, Zone::factory()->create()->id, null)],
    'reprice a drifted line' => [function (): void {
        $this->resolver->base = 800;
        $this->cartManager->reprice($this->cart);
    }],
    'set a line price' => [fn () => $this->cartManager->setLinePrice($this->cart, $this->line->id, 500)],
    'clear a line price' => [fn () => $this->cartManager->clearLinePrice($this->cart, $this->line->id)],
    'apply a coupon' => [fn () => $this->cartManager->applyCoupon($this->cart, Discount::factory()->create(['code' => 'NEW'])->code)],
    'remove a coupon' => [function (): void {
        $this->cart->promotions()->create(['discount_id' => Discount::factory()->create(['code' => 'OLD'])->id, 'source' => PromotionSource::Code->value, 'code' => 'OLD']);
        $this->cartManager->removeCoupon($this->cart, 'OLD');
    }],
    'merge into another cart' => [fn () => $this->cartManager->merge($this->cart, Cart::factory()->create(['currency_code' => 'USD']))],
]);

it('releases an unpaid payment session before changing the total', function (): void {
    $this->cartManager->setPaymentSession($this->cart, $this->session);

    $this->cartManager->add($this->cart, $this->other);

    expect($this->gateway->releases)->toBe(1)
        ->and($this->cart->refresh()->payment_session)->toBeNull()
        ->and($this->cart->lines()->count())->toBe(2);
});

it('releases the payment session another request opened while the change was waiting', function (): void {
    DB::table($this->cart->getTable())->where('id', $this->cart->id)->update(['payment_session' => json_encode($this->session)]);

    $this->cartManager->add($this->cart, $this->other);

    expect($this->gateway->releases)->toBe(1)
        ->and($this->cart->refresh()->payment_session)->toBeNull();
});

it('never asks the provider about a cart without payment session', function (): void {
    $this->cartManager->add($this->cart, $this->other);
    $this->cartManager->changeCurrency($this->cart, 'EUR');

    expect($this->gateway->releases)->toBe(0);
});

it('keeps the payment session when a change leaves the total as it is', function (Closure $setup, Closure $mutation): void {
    $setup->call($this);
    $total = $this->cartManager->calculate($this->cart)->total;
    $this->gateway->paid = true;
    $this->cartManager->setPaymentSession($this->cart, $this->session);

    $mutation->call($this);

    expect($this->gateway->releases)->toBe(0)
        ->and($this->cart->refresh()->payment_session)->toEqual($this->session)
        ->and($this->cartManager->calculate($this->cart)->total)->toBe($total);
})->with([
    'same address' => [
        fn () => $this->cartManager->addAddress($this->cart, AddressType::Shipping, $this->address),
        fn () => $this->cartManager->addAddress($this->cart, AddressType::Shipping, $this->address),
    ],
    'same shipping method' => [
        fn () => $this->cartManager->setShippingMethod($this->cart, 'fedex:ground', 500),
        fn () => $this->cartManager->setShippingMethod($this->cart, 'fedex:ground', 500),
    ],
    'same quantity' => [
        fn (): null => null,
        fn () => $this->cartManager->update($this->cart, $this->line->id, ['quantity' => 1]),
    ],
    'line metadata only' => [
        fn (): null => null,
        fn () => $this->cartManager->update($this->cart, $this->line->id, ['metadata' => ['gift' => true]]),
    ],
    'same coupon' => [
        fn () => $this->cartManager->applyCoupon($this->cart, Discount::factory()->create(['code' => 'SAME'])->code),
        fn () => $this->cartManager->applyCoupon($this->cart, 'SAME'),
    ],
    'billing address' => [
        fn (): null => null,
        fn () => $this->cartManager->addAddress($this->cart, AddressType::Billing, $this->address),
    ],
    'no coupon to remove' => [
        fn (): null => null,
        fn () => $this->cartManager->removeCoupon($this->cart),
    ],
    'another coupon code' => [
        function (): void {
            Discount::factory()->create([
                'code' => 'KEEP', 'is_active' => true, 'type' => DiscountType::Percentage, 'value' => 20,
                'apply_to' => DiscountApplyTo::Order, 'eligibility' => DiscountEligibility::Everyone,
                'min_required' => DiscountRequirement::None, 'start_at' => now()->subDay(),
            ]);
            $this->cartManager->applyCoupon($this->cart, 'KEEP');
        },
        fn () => $this->cartManager->removeCoupon($this->cart, 'NOPE'),
    ],
    'same zone and channel' => [
        function (): void {
            $this->cart->update(['zone_id' => Zone::factory()->create()->id]);
            $this->cartManager->setShippingMethod($this->cart, 'fedex:ground', 500);
        },
        fn () => $this->cartManager->changeContext($this->cart, $this->cart->zone_id, null),
    ],
    'reprice without drift' => [
        fn (): null => null,
        fn () => $this->cartManager->reprice($this->cart),
    ],
    'remove a line that is gone' => [
        fn (): null => null,
        fn () => rescue(fn () => $this->cartManager->remove($this->cart, 0), report: false),
    ],
]);

it('drops a manual payment session when the payment method changes', function (): void {
    $this->cartManager->setPaymentSession($this->cart, ['driver' => 'manual', 'reference' => 'manual_1', 'amount' => 1000]);

    $this->cartManager->setPaymentMethod($this->cart, PaymentMethod::factory()->create()->id);

    expect($this->cart->refresh()->payment_session)->toBeNull();
});

it('compares a change with the cart as stored, not as the request first read it', function (): void {
    $method = PaymentMethod::factory()->create();
    $stale = Cart::query()->find($this->cart->id);
    $this->cart->update(['payment_method_id' => PaymentMethod::factory()->create()->id]);
    $this->cartManager->setPaymentSession($this->cart, $this->session);
    $stale->forceFill(['payment_method_id' => $method->id]);

    $this->cartManager->setPaymentMethod($stale, $method->id);

    expect($this->gateway->releases)->toBe(1)
        ->and($this->cart->refresh()->payment_method_id)->toBe($method->id)
        ->and($this->cart->payment_session)->toBeNull();
});

it('refuses a change on a cart deleted in the meantime', function (): void {
    DB::table($this->cart->getTable())->where('id', $this->cart->id)->delete();

    $this->cartManager->add($this->cart, $this->other);
})->throws(ModelNotFoundException::class);

it('keeps a manual session when a line changes and drops it when the currency changes', function (): void {
    $this->cartManager->setPaymentSession($this->cart, ['driver' => 'manual', 'reference' => 'manual_1', 'amount' => 1000]);

    $this->cartManager->update($this->cart, $this->line->id, ['quantity' => 2]);

    expect($this->cart->refresh()->payment_session['reference'])->toBe('manual_1');

    $this->cartManager->changeCurrency($this->cart, 'EUR');

    expect($this->cart->refresh()->payment_session)->toBeNull()
        ->and($this->gateway->releases)->toBe(1);
});

it('never writes a payment session on a completed cart', function (): void {
    DB::table($this->cart->getTable())->where('id', $this->cart->id)->update(['completed_at' => now()]);

    expect($this->cartManager->setPaymentSession($this->cart, $this->session))->toBeFalse()
        ->and(DB::table($this->cart->getTable())->where('id', $this->cart->id)->value('payment_session'))->toBeNull();
});

it('writes the payment session through the attribute mutator of an extended cart model', function (): void {
    config(['shopper.cart.models.cart' => EnvelopedSessionCart::class]);
    $cart = EnvelopedSessionCart::query()->findOrFail($this->cart->id);

    $this->cartManager->setPaymentSession($cart, $this->session);

    expect(EnvelopedSessionCart::query()->findOrFail($cart->id)->payment_session)->toEqual($this->session);
});

it('waits for the payment session lock before changing the total', function (): void {
    Sleep::fake(syncWithCarbon: true);
    Cache::lock("cart:payment-session:{$this->cart->public_id}", 30)->get();

    expect(fn () => $this->cartManager->add($this->cart, $this->other))->toThrow(LockTimeoutException::class)
        ->and($this->cart->lines()->count())->toBe(1);
});

it('leaves the session in memory untouched when a completed cart refuses the write', function (): void {
    $this->cartManager->setPaymentSession($this->cart, ['driver' => 'manual', 'reference' => 'manual_1', 'amount' => 1000]);
    DB::table($this->cart->getTable())->where('id', $this->cart->id)->update(['completed_at' => now()]);

    expect($this->cartManager->setPaymentSession($this->cart, $this->session))->toBeFalse()
        ->and($this->cart->payment_session['reference'])->toBe('manual_1');
});

it('never releases the payment session of a completed cart', function (): void {
    $this->cartManager->setPaymentSession($this->cart, $this->session);
    DB::table($this->cart->getTable())->where('id', $this->cart->id)->update(['completed_at' => now()]);

    expect(fn () => $this->cartManager->releasePaymentSession($this->cart))->toThrow(CartCompletedException::class)
        ->and($this->gateway->releases)->toBe(0);
});
