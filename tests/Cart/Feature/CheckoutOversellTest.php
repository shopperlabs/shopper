<?php

declare(strict_types=1);

use Shopper\Cart\Actions\CreateOrderFromCartAction;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\InsufficientStockException;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Product;
use Tests\Core\Stubs\User;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->user = User::factory()->create();
    $this->cartManager = resolve(CartManager::class);
    $this->action = resolve(CreateOrderFromCartAction::class);
    $this->inventory = Inventory::factory()->create([
        'is_default' => true,
        'priority' => 0,
    ]);

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create([
        'amount' => 25,
        'currency_id' => $this->currency->id,
    ]);
    $this->product->load('prices');

    $this->cart = Cart::query()->create([
        'currency_code' => 'USD',
        'customer_id' => $this->user->id,
    ]);
});

describe('CheckoutOversellTest', function (): void {
    it('aborts checkout and creates no order when stock is drained after the line is added', function (): void {
        $this->product->mutateStock($this->inventory->id, 1, event: 'Initial');
        $this->cartManager->add($this->cart, $this->product, quantity: 1);

        $this->product->decreaseStock($this->inventory->id, 1);

        expect(fn (): Order => $this->action->execute($this->cart->refresh()))
            ->toThrow(InsufficientStockException::class);

        expect(Order::query()->count())->toBe(0)
            ->and($this->product->getStock())->toBe(0);
    });

    it('completes checkout for a back-orderable product with zero stock', function (): void {
        $product = Product::factory()->standard()->create(['allow_backorder' => true]);
        $product->prices()->create([
            'amount' => 25,
            'currency_id' => $this->currency->id,
        ]);
        $product->load('prices');

        $this->cartManager->add($this->cart, $product, quantity: 2);

        $order = $this->action->execute($this->cart->refresh());

        expect(Order::query()->count())->toBe(1)
            ->and($order->items)->toHaveCount(1)
            ->and($product->getStock())->toBe(-2);
    });

    it('decrements stock inside the checkout transaction', function (): void {
        $this->product->mutateStock($this->inventory->id, 10, event: 'Initial');
        $this->cartManager->add($this->cart, $this->product, quantity: 3);

        $this->action->execute($this->cart->refresh());

        expect($this->product->getStock())->toBe(7);
    });
})->group('workflows', 'stock-allocation');
