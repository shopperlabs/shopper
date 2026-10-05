<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Shopper\Cart\Actions\CreateOrderFromCartAction;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\QuantityRuleViolationException;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Contracts\QuantityRuleResolver;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Pricing\PricingContext;
use Shopper\Core\Pricing\QuantityRule;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->rules = new class implements QuantityRuleResolver
    {
        public ?QuantityRule $rule = null;

        public ?PricingContext $context = null;

        public function resolve(Priceable&Model $purchasable, PricingContext $context): ?QuantityRule
        {
            $this->context = $context;

            return $this->rule;
        }
    };
    $this->app->instance(QuantityRuleResolver::class, $this->rules);
    $this->cartManager = resolve(CartManager::class);

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create([
        'amount' => 1000,
        'currency_id' => Currency::query()->where('code', 'USD')->value('id'),
    ]);
    $this->product->load('prices');
    $this->product->mutateStock(Inventory::factory()->create()->id, 200);

    $this->cart = Cart::factory()->create(['currency_code' => 'USD']);
});

describe(QuantityRule::class, function (): void {
    it('allows a quantity within the minimum, maximum and increment', function (int $quantity, bool $allowed): void {
        expect((new QuantityRule(minimum: 12, maximum: 60, increment: 6))->allows($quantity))->toBe($allowed);
    })->with([
        [6, false],
        [12, true],
        [15, false],
        [18, true],
        [60, true],
        [66, false],
    ]);

    it('rejects a rule that contradicts itself', function (int $minimum, ?int $maximum, int $increment): void {
        new QuantityRule(minimum: $minimum, maximum: $maximum, increment: $increment);
    })->with([
        [0, null, 1],
        [1, null, 0],
        [10, 5, 1],
        [5, null, 2],
    ])->throws(InvalidArgumentException::class);
});

describe('CartManager quantity rules', function (): void {
    it('adds any quantity when no rule resolves', function (): void {
        expect($this->cartManager->add($this->cart, $this->product, quantity: 5)->quantity)->toBe(5);
    });

    it('rejects an add below the minimum and reports the rule', function (): void {
        $this->rules->rule = new QuantityRule(minimum: 12, increment: 6);

        try {
            $this->cartManager->add($this->cart, $this->product, quantity: 6);

            $this->fail('The quantity rule was not enforced.');
        } catch (QuantityRuleViolationException $exception) {
            expect($exception->rule->minimum)->toBe(12)
                ->and($exception->quantity)->toBe(6)
                ->and($this->cart->lines()->count())->toBe(0);
        }
    });

    it('checks the rule on the quantity after folding into an existing line', function (): void {
        $this->rules->rule = new QuantityRule(minimum: 12, increment: 6);
        $this->cartManager->add($this->cart, $this->product, quantity: 12);

        $line = $this->cartManager->add($this->cart, $this->product, quantity: 6);

        expect($line->quantity)->toBe(18);

        $this->cartManager->add($this->cart, $this->product, quantity: 1);
    })->throws(QuantityRuleViolationException::class);

    it('rejects an update outside the rule', function (): void {
        $this->rules->rule = new QuantityRule(minimum: 12, maximum: 24, increment: 6);
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 12);

        $this->cartManager->update($this->cart, $line->id, ['quantity' => 30]);
    })->throws(QuantityRuleViolationException::class);
});

describe('Quantity rule context', function (): void {
    it('passes the quantity of the whole product, requested quantity included', function (): void {
        $currency = Currency::query()->where('code', 'USD')->value('id');
        [$small, $large] = collect(['S', 'L'])->map(function () use ($currency): ProductVariant {
            $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
            $variant->prices()->create(['amount' => 1000, 'currency_id' => $currency]);
            $variant->mutateStock(Inventory::query()->value('id'), 50);

            return $variant;
        })->all();

        $this->cartManager->add($this->cart, $small, quantity: 6);
        $line = $this->cartManager->add($this->cart, $large, quantity: 4);

        expect($this->rules->context->quantity)->toBe(4)
            ->and($this->rules->context->productQuantity())->toBe(10);

        $this->cartManager->update($this->cart, $line->id, ['quantity' => 2]);

        expect($this->rules->context->productQuantity())->toBe(8);
    });
});

describe('Checkout quantity rules', function (): void {
    it('rechecks quantity rules at checkout', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 3);
        $this->rules->rule = new QuantityRule(minimum: 12);

        resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh());
    })->throws(QuantityRuleViolationException::class);

    it('honours a paid quantity that a rule now refuses', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 3);
        $this->rules->rule = new QuantityRule(minimum: 12);

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh(), honoursPayment: fn (): bool => true);

        expect($order->items->first()->quantity)->toBe(3);
    });

    it('exempts a manually priced line from quantity rules', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 3);
        $this->cartManager->setLinePrice($this->cart, $line->id, 700);
        $this->rules->rule = new QuantityRule(minimum: 12);

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh());

        expect($order->items->first()->quantity)->toBe(3);
    });
});
