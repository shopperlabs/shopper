<?php

declare(strict_types=1);

use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Pipelines\Calculate;
use Shopper\Cart\Pipelines\CalculateLines;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxZone;
use Tests\Core\Stubs\User;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->user = User::factory()->create();
    $this->cartManager = resolve(CartManager::class);
    $this->inventory = Inventory::factory()->create();

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create([
        'amount' => 2500,
        'currency_id' => $this->currency->id,
    ]);
    $this->product->load('prices');
    $this->product->mutateStock($this->inventory->id, 100);

    $this->cart = Cart::query()->create([
        'currency_code' => 'USD',
        'customer_id' => $this->user->id,
    ]);
});

describe(CalculateLines::class, function (): void {
    it('calculates subtotal for a single line', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $context = $this->cartManager->calculate($this->cart);

        expect($context)->toBeInstanceOf(CartPipelineContext::class)
            ->and($context->subtotal)->toBe(5000);
    });

    it('calculates subtotal for multiple lines', function (): void {
        $product2 = Product::factory()->standard()->create();
        $product2->prices()->create([
            'amount' => 1000,
            'currency_id' => $this->currency->id,
        ]);
        $product2->load('prices');
        $product2->mutateStock($this->inventory->id, 100);

        $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->cartManager->add($this->cart, $product2, quantity: 3);

        $context = $this->cartManager->calculate($this->cart);

        expect($context->subtotal)->toBe(8000);
    });

    it('calculates total without discount or tax', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 1);

        $context = $this->cartManager->calculate($this->cart);

        expect($context->total)->toBe(2500)
            ->and($context->discountTotal)->toBe(0)
            ->and($context->taxTotal)->toBe(0);
    });

    it('applies percentage discount to order total', function (): void {
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

        $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->cartManager->applyCoupon($this->cart, 'SAVE20');

        $context = $this->cartManager->calculate($this->cart->refresh());

        expect($context->subtotal)->toBe(5000)
            ->and($context->discountTotal)->toBe(1000)
            ->and($context->total)->toBe(4000);
    });

    it('applies fixed amount discount distributed across lines', function (): void {
        $product2 = Product::factory()->standard()->create();
        $product2->prices()->create([
            'amount' => 1500,
            'currency_id' => $this->currency->id,
        ]);
        $product2->load('prices');
        $product2->mutateStock($this->inventory->id, 100);

        Discount::factory()->create([
            'code' => 'FLAT10',
            'is_active' => true,
            'type' => DiscountType::FixedAmount,
            'value' => 1000,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'start_at' => now()->subDay(),
            'end_at' => now()->addMonth(),
        ]);

        $this->cartManager->add($this->cart, $this->product, quantity: 1);
        $this->cartManager->add($this->cart, $product2, quantity: 1);
        $this->cartManager->applyCoupon($this->cart, 'FLAT10');

        $context = $this->cartManager->calculate($this->cart->refresh());

        expect($context->subtotal)->toBe(4000)
            ->and($context->discountTotal)->toBe(1000)
            ->and($context->total)->toBe(3000);
    });

    it('calculates tax on lines after discount', function (): void {
        $country = Country::query()->where('cca2', 'US')->first()
            ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

        $taxZone = TaxZone::factory()->create([
            'country_id' => $country->id,
            'is_tax_inclusive' => false,
        ]);

        TaxRate::factory()->create([
            'tax_zone_id' => $taxZone->id,
            'rate' => 10.00,
            'is_default' => true,
        ]);

        $this->cartManager->add($this->cart, $this->product, quantity: 1);
        $this->cartManager->addAddress($this->cart, AddressType::Shipping, [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_1' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
            'country_id' => $country->id,
        ]);

        $context = $this->cartManager->calculate($this->cart->refresh());

        expect($context->subtotal)->toBe(2500)
            ->and($context->taxTotal)->toBe(250)
            ->and($context->taxInclusive)->toBeFalse()
            ->and($context->total)->toBe(2750);
    });

    it('handles tax inclusive mode', function (): void {
        $country = Country::query()->where('cca2', 'FR')->first()
            ?? Country::factory()->create(['cca2' => 'FR', 'name' => 'France']);

        $taxZone = TaxZone::factory()->create([
            'country_id' => $country->id,
            'is_tax_inclusive' => true,
        ]);

        TaxRate::factory()->create([
            'tax_zone_id' => $taxZone->id,
            'rate' => 20.00,
            'is_default' => true,
        ]);

        $this->cartManager->add($this->cart, $this->product, quantity: 1);
        $this->cartManager->addAddress($this->cart, AddressType::Shipping, [
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'address_1' => '1 Rue de Paris',
            'city' => 'Paris',
            'postal_code' => '75001',
            'country_id' => $country->id,
        ]);

        $context = $this->cartManager->calculate($this->cart->refresh());

        expect($context->taxInclusive)->toBeTrue()
            ->and($context->total)->toBe($context->subtotal);
    });

    it('skips tax calculation when no shipping address', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 1);

        $context = $this->cartManager->calculate($this->cart);

        expect($context->taxTotal)->toBe(0);
    });

    it('supports custom pipeline via config', function (): void {
        config()->set('shopper.cart.pipelines.cart', [
            CalculateLines::class,
            Calculate::class,
        ]);

        Discount::factory()->create([
            'code' => 'SKIP',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 50,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'start_at' => now()->subDay(),
            'end_at' => now()->addMonth(),
        ]);

        $this->cartManager->add($this->cart, $this->product, quantity: 1);
        $this->cartManager->applyCoupon($this->cart, 'SKIP');

        $context = $this->cartManager->calculate($this->cart->refresh());

        expect($context->discountTotal)->toBe(0)
            ->and($context->total)->toBe(2500);
    });
})->group('cart', 'cart-calculation');
