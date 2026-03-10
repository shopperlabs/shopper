<?php

declare(strict_types=1);

use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;
use Shopper\Core\Models\Inventory;
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

    $this->cart = Cart::query()->create([
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

        DiscountDetail::query()->create([
            'discount_id' => $discount->id,
            'condition' => DiscountCondition::Eligibility,
            'discountable_type' => $this->user->getMorphClass(),
            'discountable_id' => $this->user->id,
            'total_use' => 1,
        ]);

        $result = $this->validator->validate($discount, $this->context);

        expect($result->valid)->toBeFalse();
    });

    it('rejects a discount requiring login when no customer', function (): void {
        $guestCart = Cart::query()->create([
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
})->group('cart', 'cart-discount-validator');
