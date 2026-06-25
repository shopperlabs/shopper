<?php

declare(strict_types=1);

use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderPromotion;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Zone;
use Tests\Core\Stubs\User;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->user = User::factory()->create();
    $this->validator = resolve(DiscountValidator::class);
    $inventory = Inventory::factory()->create();

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create([
        'amount' => 25,
        'currency_id' => $this->currency->id,
    ]);
    $this->product->load('prices');
    $this->product->mutateStock($inventory->id, 100);

    $this->cart = Cart::factory()->create([
        'currency_code' => 'USD',
        'customer_id' => $this->user->id,
    ]);

    $this->cart->lines()->create([
        'purchasable_type' => $this->product->getMorphClass(),
        'purchasable_id' => $this->product->id,
        'quantity' => 2,
        'unit_price_amount' => 25,
    ]);

    $this->context = new CartPipelineContext($this->cart);
    $this->context->subtotal = 5000;
});

function validDiscountAttributes(): array
{
    return [
        'code' => 'VALID',
        'is_active' => true,
        'type' => DiscountType::Percentage,
        'value' => 10,
        'apply_to' => DiscountApplyTo::Order,
        'eligibility' => DiscountEligibility::Everyone,
        'min_required' => DiscountRequirement::None,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
    ];
}

describe(DiscountValidator::class, function (): void {
    it('accepts a valid discount', function (): void {
        $discount = Discount::factory()->create(validDiscountAttributes());

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeTrue()
            ->and($result->failureReason)->toBeNull();
    });

    it('rejects a discount with a negative value', function (): void {
        $discount = Discount::factory()->make(array_merge(validDiscountAttributes(), [
            'type' => DiscountType::FixedAmount,
            'value' => -5000,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a discount with a zero value', function (): void {
        $discount = Discount::factory()->make(array_merge(validDiscountAttributes(), [
            'value' => 0,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a percentage discount above 100', function (): void {
        $discount = Discount::factory()->make(array_merge(validDiscountAttributes(), [
            'type' => DiscountType::Percentage,
            'value' => 150,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects an inactive discount', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'is_active' => false,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a discount that has not started', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'start_at' => now()->addWeek(),
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects an expired discount', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'start_at' => now()->subMonth(),
            'end_at' => now()->subDay(),
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a discount that reached its usage limit', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'usage_limit' => 5,
            'total_use' => 5,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a discount already used by the customer', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'usage_limit_per_user' => true,
        ]));

        $order = Order::query()->create([
            'number' => 'TEST-ORDER',
            'price_amount' => 100,
            'currency_code' => 'USD',
            'customer_id' => $this->user->id,
            'discount_id' => $discount->id,
        ]);
        OrderPromotion::query()->create([
            'order_id' => $order->id,
            'discount_id' => $discount->id,
            'code' => $discount->code,
            'type' => $discount->type->value,
            'value_at_apply' => $discount->value,
            'amount' => 100,
            'currency_code' => 'USD',
        ]);

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a per-user discount already used by a guest with the same email', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'usage_limit_per_user' => true,
        ]));

        $order = Order::query()->create([
            'number' => 'GUEST-USED',
            'price_amount' => 100,
            'currency_code' => 'USD',
            'customer_id' => null,
            'email' => 'guest@example.com',
            'discount_id' => $discount->id,
        ]);
        OrderPromotion::query()->create([
            'order_id' => $order->id,
            'discount_id' => $discount->id,
            'code' => $discount->code,
            'type' => $discount->type->value,
            'value_at_apply' => $discount->value,
            'amount' => 100,
            'currency_code' => 'USD',
        ]);

        $guestCart = Cart::factory()->create([
            'currency_code' => 'USD',
            'customer_id' => null,
            'email' => 'guest@example.com',
        ]);

        $result = $this->validator->validate($discount, new CartPipelineContext($guestCart));

        expect($result->valid)->toBeFalse();
    });

    it('allows a per-user discount for a guest with a different email', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'usage_limit_per_user' => true,
        ]));

        $order = Order::query()->create([
            'number' => 'GUEST-OTHER',
            'price_amount' => 100,
            'currency_code' => 'USD',
            'customer_id' => null,
            'email' => 'used@example.com',
            'discount_id' => $discount->id,
        ]);
        OrderPromotion::query()->create([
            'order_id' => $order->id,
            'discount_id' => $discount->id,
            'code' => $discount->code,
            'type' => $discount->type->value,
            'value_at_apply' => $discount->value,
            'amount' => 100,
            'currency_code' => 'USD',
        ]);

        $guestCart = Cart::factory()->create([
            'currency_code' => 'USD',
            'customer_id' => null,
            'email' => 'fresh@example.com',
        ]);

        $result = $this->validator->validate($discount, new CartPipelineContext($guestCart));

        expect($result->valid)->toBeTrue();
    });

    it('rejects a discount requiring login when no customer', function (): void {
        $guestCart = Cart::factory()->create([
            'currency_code' => 'USD',
            'customer_id' => null,
        ]);
        $guestContext = new CartPipelineContext($guestCart);

        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'eligibility' => DiscountEligibility::Customers,
        ]));

        $result = $this->validator->validate($discount, $guestContext);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a discount for ineligible customer', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'eligibility' => DiscountEligibility::Customers,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a discount for the wrong zone', function (): void {
        $zone = Zone::factory()->create();

        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'zone_id' => $zone->id,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a fixed-amount discount whose currency differs from the cart currency', function (): void {
        $euro = Currency::query()->firstOrCreate(
            ['code' => 'EUR'],
            ['name' => 'Euro', 'symbol' => '€', 'format' => '1.234,56 €'],
        );
        $zone = Zone::factory()->create(['currency_id' => $euro->id]);

        $this->cart->update(['zone_id' => $zone->id]);

        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'type' => DiscountType::FixedAmount,
            'value' => 5000,
            'zone_id' => $zone->id,
        ]));

        $result = $this->validator->validate($discount, new CartPipelineContext($this->cart->refresh()));

        expect($result->valid)->toBeFalse()
            ->and($result->failureReason)->toBe(__('shopper-cart::messages.discount.currency_mismatch'));
    });

    it('accepts a fixed-amount discount whose currency matches the cart currency', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'type' => DiscountType::FixedAmount,
            'value' => 1000,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeTrue();
    });

    it('rejects a discount when minimum amount is not reached', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'min_required' => DiscountRequirement::Price,
            'min_required_value' => 10000,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a discount when minimum quantity is not reached', function (): void {
        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'min_required' => DiscountRequirement::Quantity,
            'min_required_value' => 10,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a discount whose campaign budget is exhausted', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 10_000)
            ->create(['currency_code' => 'USD', 'spent_amount' => 10_000]);

        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'campaign_id' => $campaign->id,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse()
            ->and($result->failureReason)->toBe(__('shopper-cart::messages.discount.campaign_budget_reached'));
    });

    it('rejects a discount whose campaign currency differs from the cart currency', function (): void {
        $campaign = Campaign::factory()->create(['currency_code' => 'EUR']);

        $discount = Discount::factory()->create(array_merge(validDiscountAttributes(), [
            'campaign_id' => $campaign->id,
        ]));

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse()
            ->and($result->failureReason)->toBe(__('shopper-cart::messages.discount.currency_mismatch'));
    });
})->group('cart', 'cart-discount-validator');
