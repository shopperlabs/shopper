<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
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

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->cartManager = resolve(CartManager::class);
    $this->inventory = Inventory::factory()->create();

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create([
        'amount' => 10000,
        'currency_id' => $this->currency->id,
    ]);
    $this->product->load('prices');
    $this->product->mutateStock($this->inventory->id, 100);

    $this->cart = Cart::factory()->create(['currency_code' => 'USD']);

    $this->country = Country::query()->where('cca2', 'US')->first()
        ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

    $taxZone = TaxZone::factory()->create([
        'country_id' => $this->country->id,
        'is_tax_inclusive' => false,
    ]);

    TaxRate::factory()->create([
        'tax_zone_id' => $taxZone->id,
        'rate' => 20.00,
        'is_default' => true,
    ]);

    $this->cartManager->add($this->cart, $this->product);
    $this->cartManager->addAddress($this->cart, AddressType::Shipping, [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_1' => '123 Main St',
        'city' => 'New York',
        'postal_code' => '10001',
        'country_id' => $this->country->id,
    ]);
});

describe('Cart totals read path', function (): void {
    it('serves fresh totals without a single write statement', function (): void {
        $this->cartManager->calculate($this->cart->refresh());

        $cart = Cart::query()->findOrFail($this->cart->id);

        DB::enableQueryLog();
        $context = $this->cartManager->totals($cart);
        $queries = collect(DB::getQueryLog())->pluck('query');
        DB::disableQueryLog();

        $writes = $queries->filter(
            fn (string $sql): bool => (bool) preg_match('/^\s*(insert|update|delete)/i', $sql),
        );

        expect($writes)->toBeEmpty()
            ->and($context->subtotal)->toBe(10000)
            ->and($context->taxTotal)->toBe(2000)
            ->and($context->total)->toBe(12000);
    });

    it('returns the same totals from the read path and the pipeline', function (): void {
        $calculated = $this->cartManager->calculate($this->cart->refresh());
        $read = $this->cartManager->totals(Cart::query()->findOrFail($this->cart->id));

        expect($read->subtotal)->toBe($calculated->subtotal)
            ->and($read->discountTotal)->toBe($calculated->discountTotal)
            ->and($read->taxTotal)->toBe($calculated->taxTotal)
            ->and($read->taxInclusive)->toBe($calculated->taxInclusive)
            ->and($read->total)->toBe($calculated->total);
    });

    it('recalculates once after a mutation invalidated the totals', function (): void {
        $this->cartManager->calculate($this->cart->refresh());

        $other = Product::factory()->standard()->create();
        $other->prices()->create(['amount' => 5000, 'currency_id' => $this->currency->id]);
        $other->load('prices');
        $other->mutateStock($this->inventory->id, 50);

        $this->cartManager->add($this->cart, $other);

        expect($this->cart->refresh()->calculated_at)->toBeNull();

        $context = $this->cartManager->totals($this->cart->refresh());

        expect($context->subtotal)->toBe(15000)
            ->and($context->taxTotal)->toBe(3000)
            ->and($this->cart->refresh()->calculated_at)->not->toBeNull();
    });

    it('recalculates a cart whose totals aged past the freshness window and drops an expired promotion', function (): void {
        Discount::factory()->create([
            'code' => 'FLASH',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 50,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'start_at' => now()->subDay(),
            'end_at' => now()->addMinutes(5),
        ]);

        $this->cartManager->applyCoupon($this->cart->refresh(), 'FLASH');
        $context = $this->cartManager->calculate($this->cart->refresh());

        expect($context->discountTotal)->toBe(5000);

        $this->travel(30)->minutes();

        $stale = $this->cartManager->totals(Cart::query()->findOrFail($this->cart->id));

        expect($stale->discountTotal)->toBe(0)
            ->and($stale->total)->toBe(12000);

        $this->travelBack();
    });
})->group('cart', 'totals');
