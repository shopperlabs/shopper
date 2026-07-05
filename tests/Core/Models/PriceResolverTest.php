<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Contracts\PriceResolver;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Pricing\PricingContext;
use Shopper\Core\Pricing\ResolvedPrice;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();

    $this->inventory = Inventory::factory()->create();

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create([
        'amount' => 2500,
        'compare_amount' => 3000,
        'currency_id' => $this->currency->id,
    ]);
    $this->product->load('prices');
    $this->product->mutateStock($this->inventory->id, 50);
});

describe(PriceResolver::class, function (): void {
    it('resolves the catalog price by default', function (): void {
        $resolved = $this->product->resolvePrice(new PricingContext(currencyCode: 'USD'));

        expect($resolved)->toBeInstanceOf(ResolvedPrice::class)
            ->and($resolved->amount)->toBe(2500)
            ->and($resolved->compareAmount)->toBe(3000)
            ->and($resolved->currencyCode)->toBe('USD')
            ->and($resolved->originalAmount)->toBeNull();
    });

    it('returns null when no price exists for the currency', function (): void {
        $resolved = $this->product->resolvePrice(new PricingContext(currencyCode: 'EUR'));

        expect($resolved)->toBeNull();
    });

    it('uses the swapped resolver when a cart line is created', function (): void {
        app()->singleton(PriceResolver::class, fn () => new class implements PriceResolver
        {
            public function resolve(Priceable&Model $priceable, PricingContext $context): ?ResolvedPrice
            {
                return new ResolvedPrice(
                    amount: 1500,
                    currencyCode: $context->currency(),
                    originalAmount: 2500,
                );
            }
        });

        $cart = Cart::factory()->create(['currency_code' => 'USD']);

        $line = resolve(CartManager::class)->add($cart, $this->product);

        expect($line->unit_price_amount)->toBe(1500);
    });

    it('passes the cart context to the resolver', function (): void {
        $holder = new stdClass;

        app()->singleton(PriceResolver::class, fn () => new class($holder) implements PriceResolver
        {
            public function __construct(private stdClass $holder) {}

            public function resolve(Priceable&Model $priceable, PricingContext $context): ?ResolvedPrice
            {
                $this->holder->context = $context;

                return new ResolvedPrice(amount: 100, currencyCode: $context->currency());
            }
        });

        $cart = Cart::factory()->create(['currency_code' => 'USD']);

        resolve(CartManager::class)->add($cart, $this->product, quantity: 3);

        expect($holder->context)->toBeInstanceOf(PricingContext::class)
            ->and($holder->context->currencyCode)->toBe('USD')
            ->and($holder->context->quantity)->toBe(3);
    });
});
