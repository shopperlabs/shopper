<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Cart\Actions\CreateOrderFromCartAction;
use Shopper\Cart\CartManager;
use Shopper\Cart\Events\CartCompleted;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\OrderAddress;
use Shopper\Core\Models\Product;
use Tests\Core\Stubs\User;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->user = User::factory()->create();
    $this->cartManager = resolve(CartManager::class);
    $this->action = resolve(CreateOrderFromCartAction::class);
    $this->inventory = Inventory::factory()->create();

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create([
        'amount' => 25,
        'currency_id' => $this->currency->id,
    ]);
    $this->product->load('prices');
    $this->product->mutateStock($this->inventory->id, 100);

    $this->cart = Cart::query()->create([
        'currency_code' => 'USD',
        'customer_id' => $this->user->id,
    ]);
});

describe(CreateOrderFromCartAction::class, function (): void {
    it('creates an order from a cart with lines', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $order = $this->action->execute($this->cart);

        expect($order)->toBeInstanceOf(Order::class)
            ->and($order->currency_code)->toBe('USD')
            ->and($order->customer_id)->toBe($this->user->id)
            ->and($order->items)->toHaveCount(1)
            ->and($order->items->first()->quantity)->toBe(2);
    });

    it('transfers discount amount to order items', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $line = $this->cart->lines->first();
        $line->adjustments()->create([
            'amount' => 5,
            'code' => 'TEST',
            'discount_id' => null,
        ]);

        $order = $this->action->execute($this->cart->refresh());
        $orderItem = $order->items->first();

        expect($orderItem->discount_amount)->toBe(5);
    });

    it('creates order addresses from cart addresses', function (): void {
        $country = Country::query()->where('cca2', 'US')->first()
            ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->addAddress($this->cart, AddressType::Shipping, [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_1' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
            'country_id' => $country->id,
        ]);

        $order = $this->action->execute($this->cart->refresh());

        expect($order->shippingAddress)->toBeInstanceOf(OrderAddress::class)
            ->and($order->shippingAddress->first_name)->toBe('John')
            ->and($order->shippingAddress->city)->toBe('New York')
            ->and($order->shippingAddress->country_name)->toBe('United States');
    });

    it('marks the cart as completed after order creation', function (): void {
        $this->cartManager->add($this->cart, $this->product);

        $this->action->execute($this->cart);

        expect($this->cart->refresh()->isCompleted())->toBeTrue();
    });

    it('dispatches `CartCompleted` event', function (): void {
        Event::fake([CartCompleted::class]);

        $this->cartManager->add($this->cart, $this->product);

        $this->action->execute($this->cart);

        Event::assertDispatched(CartCompleted::class, fn (CartCompleted $event): bool => $event->cart->id === $this->cart->id);
    });

    it('increments discount `total_use` when coupon is applied', function (): void {
        $discount = Discount::factory()->create([
            'code' => 'SAVE10',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
            'usage_limit' => 100,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'SAVE10');

        $this->action->execute($this->cart->refresh());

        expect($discount->refresh()->total_use)->toBe(1);
    });

    it('creates the order without a discount when the applied coupon is exhausted', function (): void {
        $discount = Discount::factory()->create([
            'code' => 'LIMITED',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 5,
            'usage_limit' => 5,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'LIMITED');

        $order = $this->action->execute($this->cart->refresh());

        expect($order->discount_id)->toBeNull()
            ->and($order->discount_code)->toBeNull()
            ->and($discount->refresh()->total_use)->toBe(5)
            ->and($this->cart->refresh()->isCompleted())->toBeTrue();
    });

    it('does not reserve a usage slot when the coupon does not reduce the cart', function (): void {
        $other = Product::factory()->standard()->create();

        $discount = Discount::factory()->create([
            'code' => 'PRODUCTS_ONLY',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
            'usage_limit' => 100,
            'apply_to' => DiscountApplyTo::Products,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
        ]);
        $discount->items()->create([
            'discountable_id' => $other->id,
            'discountable_type' => $other->getMorphClass(),
            'condition' => DiscountCondition::ApplyTo,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'PRODUCTS_ONLY');

        $order = $this->action->execute($this->cart->refresh());

        expect($order->discount_id)->toBeNull()
            ->and($discount->refresh()->total_use)->toBe(0);
    });

    it('snapshots the discount fields onto the order', function (): void {
        $discount = Discount::factory()->create([
            'code' => 'SNAP10',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
            'usage_limit' => null,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'SNAP10');

        $order = $this->action->execute($this->cart->refresh());

        expect($order->discount_id)->toBe($discount->id)
            ->and($order->discount_code)->toBe('SNAP10')
            ->and($order->discount_type)->toBe(DiscountType::Percentage->value)
            ->and($order->discount_value_at_apply)->toBe(10)
            ->and($order->discount_currency_code)->toBe('USD');
    });

    it('does not let a customer redeem a one-use-per-customer discount twice', function (): void {
        $discount = Discount::factory()->create([
            'code' => 'ONCE',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
            'usage_limit' => null,
            'usage_limit_per_user' => true,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'ONCE');

        $this->action->execute($this->cart->refresh());

        $secondCart = Cart::query()->create([
            'currency_code' => 'USD',
            'customer_id' => $this->user->id,
        ]);
        $this->cartManager->add($secondCart, $this->product);
        $this->cartManager->applyCoupon($secondCart, 'ONCE');

        $secondOrder = $this->action->execute($secondCart->refresh());

        expect($secondOrder->discount_id)->toBeNull()
            ->and($discount->refresh()->total_use)->toBe(1)
            ->and(Order::query()->where('discount_id', $discount->id)->count())->toBe(1);
    });

    it('throws `CartCompletedException` for already completed cart', function (): void {
        $this->cart->update(['completed_at' => now()]);

        $this->action->execute($this->cart->refresh());
    })->throws(CartCompletedException::class);
})->group('cart', 'cart-order');

it('copies the checkout selections from the cart onto the order', function (): void {
    $method = PaymentMethod::factory()->create();
    $carrier = Carrier::factory()->create(['slug' => 'main-carrier', 'is_enabled' => true]);
    $option = CarrierOption::factory()->create(['carrier_id' => $carrier->id, 'price' => 950]);

    $this->cartManager->add($this->cart, $this->product);
    $this->cart->update([
        'payment_method_id' => $method->id,
        'shipping_option_id' => "main-carrier:{$option->public_id}",
        'shipping_amount' => 950,
    ]);

    $order = $this->action->execute($this->cart->refresh());

    expect($order->payment_method_id)->toBe($method->id)
        ->and($order->shipping_amount)->toBe(950)
        ->and($order->shipping_option_id)->toBe($option->id)
        ->and($order->price_amount)->toBe(25 + 950);
});

it('leaves `shipping_option_id` empty for live-carrier rates and still freezes the amount', function (): void {
    $this->cartManager->add($this->cart, $this->product);
    $this->cart->update([
        'shipping_option_id' => 'ups:express-03',
        'shipping_amount' => 1295,
    ]);

    $order = $this->action->execute($this->cart->refresh());

    expect($order->shipping_option_id)->toBeNull()
        ->and($order->shipping_amount)->toBe(1295)
        ->and($order->price_amount)->toBe(25 + 1295);
});
