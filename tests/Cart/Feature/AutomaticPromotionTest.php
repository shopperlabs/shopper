<?php

declare(strict_types=1);

use Shopper\Cart\Actions\CreateOrderFromCartAction;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
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

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create(['amount' => 2500, 'currency_id' => $this->currency->id]);
    $this->product->load('prices');
    $this->product->mutateStock($this->inventory->id, 100);

    $this->cart = Cart::factory()->create([
        'currency_code' => 'USD',
        'customer_id' => User::factory()->create()->id,
    ]);

    $this->makeAutomatic = fn (array $overrides = []): Discount => Discount::factory()->create(array_merge([
        'trigger' => PromotionSource::Automatic->value,
        'code' => null,
        'is_active' => true,
        'type' => DiscountType::Percentage,
        'value' => 10,
        'apply_to' => DiscountApplyTo::Order,
        'eligibility' => DiscountEligibility::Everyone,
        'min_required' => DiscountRequirement::None,
        'combinable' => true,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
    ], $overrides));
});

describe('Automatic promotions', function (): void {
    it('applies an automatic promotion without any code', function (): void {
        ($this->makeAutomatic)();
        $this->cartManager->add($this->cart, $this->product, quantity: 2); // 5000

        $context = $this->cartManager->calculate($this->cart->refresh());

        $promotion = $this->cart->refresh()->promotions->first();

        expect($context->discountTotal)->toBe(500)
            ->and($promotion->source)->toBe(PromotionSource::Automatic)
            ->and($promotion->code)->toBeNull()
            ->and($promotion->computed_amount)->toBe(500);
    });

    it('materialises and drops the promotion as the cart crosses its minimum', function (): void {
        ($this->makeAutomatic)(['min_required' => DiscountRequirement::Price, 'min_required_value' => 6000]);

        $line = $this->cartManager->add($this->cart, $this->product, quantity: 2); // 5000 < 6000
        $this->cartManager->calculate($this->cart->refresh());

        expect($this->cart->refresh()->promotions)->toBeEmpty();

        $this->cartManager->update($this->cart, $line->id, ['quantity' => 3]); // 7500 >= 6000
        $context = $this->cartManager->calculate($this->cart->refresh());

        expect($this->cart->refresh()->promotions)->toHaveCount(1)
            ->and($context->discountTotal)->toBe(750);
    });

    it('stacks an automatic promotion with a code promotion', function (): void {
        ($this->makeAutomatic)(['value' => 10]);
        Discount::factory()->create([
            'code' => 'SAVE5', 'is_active' => true, 'type' => DiscountType::Percentage, 'value' => 5,
            'apply_to' => DiscountApplyTo::Order, 'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None, 'combinable' => true,
        ]);

        $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->cartManager->applyCoupon($this->cart, 'SAVE5');

        $context = $this->cartManager->calculate($this->cart->refresh());

        $sources = $this->cart->refresh()->promotions->pluck('source');

        expect($context->discountTotal)->toBe(750) // 10% + 5% of 5000
            ->and($sources)->toContain(PromotionSource::Automatic, PromotionSource::Code);
    });

    it('keeps the automatic promotion when a coupon is removed', function (): void {
        ($this->makeAutomatic)();
        $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->cartManager->calculate($this->cart->refresh());

        $this->cartManager->removeCoupon($this->cart->refresh());

        expect($this->cart->refresh()->promotions)->toHaveCount(1)
            ->and($this->cart->promotions->first()->source)->toBe(PromotionSource::Automatic);
    });

    it('snapshots an automatic promotion onto the order at checkout', function (): void {
        $discount = ($this->makeAutomatic)();
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh());

        expect($order->promotions)->toHaveCount(1)
            ->and($order->promotions->first()->code)->toBeNull()
            ->and($order->promotions->first()->amount)->toBe(500)
            ->and($discount->refresh()->total_use)->toBe(1);
    });

    it('removes the automatic promotion when the discount is deactivated mid-session', function (): void {
        $discount = ($this->makeAutomatic)();
        $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->cartManager->calculate($this->cart->refresh());

        expect($this->cart->refresh()->promotions)->toHaveCount(1);

        $discount->update(['is_active' => false]);
        $this->cartManager->calculate($this->cart->refresh());

        expect($this->cart->refresh()->promotions)->toBeEmpty();
    });

    it('does not snapshot a suppressed automatic promotion onto the order', function (): void {
        ($this->makeAutomatic)(['value' => 10, 'combinable' => false, 'priority' => 5]);
        Discount::factory()->create([
            'code' => 'EXCL30', 'is_active' => true, 'type' => DiscountType::Percentage, 'value' => 30,
            'apply_to' => DiscountApplyTo::Order, 'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None, 'combinable' => false, 'priority' => 30,
        ]);

        $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->cartManager->applyCoupon($this->cart, 'EXCL30');

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh());

        $automatic = Discount::query()->where('trigger', PromotionSource::Automatic->value)->first();

        expect($order->promotions)->toHaveCount(1)
            ->and($order->promotions->first()->code)->toBe('EXCL30')
            ->and($automatic->refresh()->total_use)->toBe(0);
    });

    it('does not create a duplicate row when `calculate()` runs twice', function (): void {
        ($this->makeAutomatic)();
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $this->cartManager->calculate($this->cart->refresh());
        $this->cartManager->calculate($this->cart->refresh());

        expect($this->cart->refresh()->promotions)->toHaveCount(1);
    });
})->group('cart', 'cart-promotions');
