<?php

declare(strict_types=1);

use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Enum\ExclusivityClass;
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
    $inventory = Inventory::factory()->create();

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create(['amount' => 2500, 'currency_id' => $this->currency->id]);
    $this->product->load('prices');
    $this->product->mutateStock($inventory->id, 100);

    $this->cart = Cart::factory()->create([
        'currency_code' => 'USD',
        'customer_id' => User::factory()->create()->id,
    ]);
    $this->cartManager->add($this->cart, $this->product, quantity: 2); // subtotal 5000

    $this->makeDiscount = fn (string $code, array $overrides = []): Discount => Discount::factory()->create(array_merge([
        'code' => $code,
        'is_active' => true,
        'type' => DiscountType::Percentage,
        'apply_to' => DiscountApplyTo::Order,
        'eligibility' => DiscountEligibility::Everyone,
        'min_required' => DiscountRequirement::None,
        'exclusivity_class' => ExclusivityClass::Order,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
    ], $overrides));
});

describe('PromotionResolver', function (): void {
    it('stacks two combinable promotions in the same class on the original base', function (): void {
        ($this->makeDiscount)('TEN', ['value' => 10, 'combinable' => true, 'priority' => 10]);
        ($this->makeDiscount)('TWENTY', ['value' => 20, 'combinable' => true, 'priority' => 20]);

        $this->cartManager->applyCoupon($this->cart, 'TEN');
        $this->cartManager->applyCoupon($this->cart, 'TWENTY');

        $context = $this->cartManager->calculate($this->cart->refresh());

        // 20% (1000) + 10% (500), both off the original 5000.
        expect($context->discountTotal)->toBe(1500);

        $byCode = $this->cart->refresh()->promotions->keyBy('code');
        expect($byCode['TWENTY']->computed_amount)->toBe(1000)
            ->and($byCode['TEN']->computed_amount)->toBe(500)
            ->and($byCode['TWENTY']->sequence)->toBe(0)
            ->and($byCode['TEN']->sequence)->toBe(1);
    });

    it('applies only the highest-priority promotion when same-class promotions are not combinable', function (): void {
        ($this->makeDiscount)('TEN', ['value' => 10, 'combinable' => false, 'priority' => 10]);
        ($this->makeDiscount)('TWENTY', ['value' => 20, 'combinable' => false, 'priority' => 20]);

        $this->cartManager->applyCoupon($this->cart, 'TEN');
        $this->cartManager->applyCoupon($this->cart, 'TWENTY');

        $context = $this->cartManager->calculate($this->cart->refresh());

        expect($context->discountTotal)->toBe(1000);

        $byCode = $this->cart->refresh()->promotions->keyBy('code');
        expect($byCode['TWENTY']->computed_amount)->toBe(1000)
            ->and($byCode['TEN']->computed_amount)->toBe(0);
    });

    it('never discounts a line below zero when promotions overlap', function (): void {
        ($this->makeDiscount)('EIGHTY_A', ['value' => 80, 'combinable' => true, 'priority' => 20]);
        ($this->makeDiscount)('EIGHTY_B', ['value' => 80, 'combinable' => true, 'priority' => 10]);

        $this->cartManager->applyCoupon($this->cart, 'EIGHTY_A');
        $this->cartManager->applyCoupon($this->cart, 'EIGHTY_B');

        $context = $this->cartManager->calculate($this->cart->refresh());

        // First takes 4000, the second is capped to the remaining 1000.
        expect($context->discountTotal)->toBe(5000)
            ->and($context->total)->toBe(0);
    });

    it('caps the number of applied promotions at the configured maximum', function (): void {
        config()->set('shopper.cart.max_promotions', 1);

        ($this->makeDiscount)('TEN', ['value' => 10, 'combinable' => true, 'priority' => 10]);
        ($this->makeDiscount)('TWENTY', ['value' => 20, 'combinable' => true, 'priority' => 20]);

        $this->cartManager->applyCoupon($this->cart, 'TEN');
        $this->cartManager->applyCoupon($this->cart, 'TWENTY');

        $context = $this->cartManager->calculate($this->cart->refresh());

        expect($context->discountTotal)->toBe(1000);

        $byCode = $this->cart->refresh()->promotions->keyBy('code');
        expect($byCode['TEN']->computed_amount)->toBe(0);
    });

    it('stacks an order-class and a product-class promotion across classes', function (): void {
        ($this->makeDiscount)('ORD20', ['value' => 20, 'combinable' => false, 'priority' => 0]);
        $product = ($this->makeDiscount)('PROD10', [
            'value' => 10,
            'combinable' => false,
            'exclusivity_class' => ExclusivityClass::Product,
            'apply_to' => DiscountApplyTo::Products,
        ]);
        $product->items()->create([
            'discountable_id' => $this->product->id,
            'discountable_type' => $this->product->getMorphClass(),
            'condition' => DiscountCondition::ApplyTo,
        ]);

        $this->cartManager->applyCoupon($this->cart, 'PROD10');
        $this->cartManager->applyCoupon($this->cart, 'ORD20');

        $context = $this->cartManager->calculate($this->cart->refresh());

        // Order class (seq 0) then product class (seq 1); both apply (different classes).
        // ORD20: 20% of 5000 = 1000. PROD10: 10% of original 5000 = 500.
        $byCode = $this->cart->refresh()->promotions->keyBy('code');
        expect($byCode['ORD20']->sequence)->toBe(0)
            ->and($byCode['PROD10']->sequence)->toBe(1)
            ->and($context->discountTotal)->toBe(1500);
    });

    it('stacks a fixed-amount then a percentage and keeps the line floor', function (): void {
        $context = $this->cartManager->calculate($this->cart->refresh());
        // Single line subtotal 5000.
        ($this->makeDiscount)('FLAT1000', ['value' => 1000, 'type' => DiscountType::FixedAmount, 'combinable' => true, 'priority' => 20]);
        ($this->makeDiscount)('PCT10', ['value' => 10, 'combinable' => true, 'priority' => 10]);

        $this->cartManager->applyCoupon($this->cart, 'FLAT1000');
        $this->cartManager->applyCoupon($this->cart, 'PCT10');

        $context = $this->cartManager->calculate($this->cart->refresh());

        // FLAT1000 first (1000), then PCT10 = 10% of original 5000 = 500. Total 1500.
        expect($context->discountTotal)->toBe(1500)
            ->and($context->total)->toBe(3500);
    });

    it('keeps the sum of line adjustments equal to discountTotal on odd subtotals', function (): void {
        $inv = Inventory::factory()->create();
        $cart = Cart::factory()->create(['currency_code' => 'USD']);

        foreach ([3333, 1667, 2001] as $price) {
            $p = Product::factory()->standard()->create();
            $p->prices()->create(['amount' => $price, 'currency_id' => $this->currency->id]);
            $p->load('prices');
            $p->mutateStock($inv->id, 50);
            $this->cartManager->add($cart, $p, quantity: 1);
        }

        ($this->makeDiscount)('PCT17', ['value' => 17, 'combinable' => true, 'priority' => 20]);
        ($this->makeDiscount)('PCT13', ['value' => 13, 'combinable' => true, 'priority' => 10]);

        $this->cartManager->applyCoupon($cart, 'PCT17');
        $this->cartManager->applyCoupon($cart, 'PCT13');

        $context = $this->cartManager->calculate($cart->refresh());

        $storedTotal = $cart->refresh()->lines
            ->flatMap(fn ($line) => $line->adjustments)
            ->sum('amount');

        expect($storedTotal)->toBe($context->discountTotal);
    });

    it('re-applies a suppressed promotion once the exclusive winner is removed', function (): void {
        ($this->makeDiscount)('EXCLUSIVE', ['value' => 30, 'combinable' => false, 'priority' => 30]);
        ($this->makeDiscount)('STACKER', ['value' => 10, 'combinable' => true, 'priority' => 10]);

        $this->cartManager->applyCoupon($this->cart, 'EXCLUSIVE');
        $this->cartManager->applyCoupon($this->cart, 'STACKER');

        $first = $this->cartManager->calculate($this->cart->refresh());
        expect($first->discountTotal)->toBe(1500) // 30% of 5000; STACKER suppressed
            ->and($this->cart->refresh()->promotions->firstWhere('code', 'STACKER')->computed_amount)->toBe(0);

        $this->cartManager->removeCoupon($this->cart->refresh(), 'EXCLUSIVE');
        $second = $this->cartManager->calculate($this->cart->refresh());

        expect($second->discountTotal)->toBe(500) // STACKER now applies alone
            ->and($this->cart->refresh()->promotions->firstWhere('code', 'STACKER')->computed_amount)->toBe(500);
    });

    it('resolves deterministically regardless of application order', function (): void {
        ($this->makeDiscount)('A', ['value' => 10, 'combinable' => true, 'priority' => 20]);
        ($this->makeDiscount)('B', ['value' => 15, 'combinable' => true, 'priority' => 10]);

        $this->cartManager->applyCoupon($this->cart, 'B');
        $this->cartManager->applyCoupon($this->cart, 'A');

        $context = $this->cartManager->calculate($this->cart->refresh());

        // A (priority 20) before B (priority 10): 500 + 750, both on the original base.
        $byCode = $this->cart->refresh()->promotions->keyBy('code');
        expect($byCode['A']->sequence)->toBe(0)
            ->and($byCode['B']->sequence)->toBe(1)
            ->and($context->discountTotal)->toBe(1250);
    });
})->group('cart', 'cart-promotions');
