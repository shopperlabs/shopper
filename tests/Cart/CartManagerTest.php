<?php

declare(strict_types=1);

use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\InsufficientStockException;
use Shopper\Cart\Exceptions\InvalidDiscountException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->user = User::factory()->create();
    $this->cartManager = resolve(CartManager::class);
    $this->inventory = Inventory::factory()->create();

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create([
        'amount' => 25,
        'currency_id' => $this->currency->id,
    ]);
    $this->product->load('prices');
    $this->product->mutateStock($this->inventory->id, 50);

    $this->cart = Cart::query()->create([
        'currency_code' => 'USD',
        'customer_id' => $this->user->id,
    ]);
});

describe(CartManager::class, function (): void {
    it('adds a product to the cart', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);

        expect($line)->toBeInstanceOf(CartLine::class)
            ->and($line->quantity)->toBe(1)
            ->and($line->unit_price_amount)->toBe(25)
            ->and($line->purchasable_type)->toBe($this->product->getMorphClass())
            ->and($line->purchasable_id)->toBe($this->product->id)
            ->and($this->cart->lines()->count())->toBe(1);
    });

    it('increments quantity when adding the same product twice', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 3);

        expect($line->quantity)->toBe(5)
            ->and($this->cart->lines()->count())->toBe(1);
    });

    it('adds a variant to the cart', function (): void {
        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
        ]);
        $variant->prices()->create([
            'amount' => 30,
            'currency_id' => $this->currency->id,
        ]);
        $variant->mutateStock($this->inventory->id, 20);

        $line = $this->cartManager->add($this->cart, $variant);

        expect($line->unit_price_amount)->toBe(30)
            ->and($line->purchasable_type)->toBe($variant->getMorphClass())
            ->and($line->purchasable_id)->toBe($variant->id);
    });

    it('updates a cart line quantity', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 1);

        $updated = $this->cartManager->update($this->cart, $line->id, ['quantity' => 5]);

        expect($updated->quantity)->toBe(5);
    });

    it('removes a cart line', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);

        $this->cartManager->remove($this->cart, $line->id);

        expect($this->cart->lines()->count())->toBe(0);
    });

    it('clears all cart lines', function (): void {
        $product2 = Product::factory()->create();
        $product2->prices()->create([
            'amount' => 15,
            'currency_id' => $this->currency->id,
        ]);
        $product2->mutateStock($this->inventory->id, 10);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->add($this->cart, $product2);

        expect($this->cart->lines()->count())->toBe(2);

        $this->cartManager->clear($this->cart);

        expect($this->cart->lines()->count())->toBe(0);
    });

    it('throws `CartCompletedException` when adding to a completed cart', function (): void {
        $this->cart->update(['completed_at' => now()]);

        $this->cartManager->add($this->cart->refresh(), $this->product);
    })->throws(CartCompletedException::class);

    it('throws `CartCompletedException` when updating a completed cart', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);
        $this->cart->update(['completed_at' => now()]);

        $this->cartManager->update($this->cart->refresh(), $line->id, ['quantity' => 5]);
    })->throws(CartCompletedException::class);

    it('throws `CartCompletedException` when removing from a completed cart', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);
        $this->cart->update(['completed_at' => now()]);

        $this->cartManager->remove($this->cart->refresh(), $line->id);
    })->throws(CartCompletedException::class);

    it('stores metadata on a cart line', function (): void {
        $metadata = ['attributes' => ['Size' => 'M', 'Color' => 'Blue']];

        $line = $this->cartManager->add($this->cart, $this->product, metadata: $metadata);

        expect($line->metadata)->toBe($metadata)
            ->and($line->metadata['attributes']['Size'])->toBe('M');
    });

    it('throws `InvalidArgumentException` when quantity is zero', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 0);
    })->throws(InvalidArgumentException::class);

    it('throws `InvalidArgumentException` when quantity is negative', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: -1);
    })->throws(InvalidArgumentException::class);

    it('throws `InsufficientStockException` when exceeding available stock', function (): void {
        $product = Product::factory()->standard()->create();
        $product->prices()->create([
            'amount' => 10,
            'currency_id' => $this->currency->id,
        ]);
        $product->load('prices');
        $product->mutateStock($this->inventory->id, 3);

        $this->cartManager->add($this->cart, $product, quantity: 5);
    })->throws(InsufficientStockException::class);

    it('throws `InsufficientStockException` when increment exceeds stock', function (): void {
        $product = Product::factory()->standard()->create();
        $product->prices()->create([
            'amount' => 10,
            'currency_id' => $this->currency->id,
        ]);
        $product->load('prices');
        $product->mutateStock($this->inventory->id, 5);

        $this->cartManager->add($this->cart, $product, quantity: 3);
        $this->cartManager->add($this->cart, $product, quantity: 4);
    })->throws(InsufficientStockException::class);

    it('allows backorder products to exceed stock', function (): void {
        $product = Product::factory()->standard()->create(['allow_backorder' => true]);
        $product->prices()->create([
            'amount' => 10,
            'currency_id' => $this->currency->id,
        ]);
        $product->load('prices');
        $product->mutateStock($this->inventory->id, 2);

        $line = $this->cartManager->add($this->cart, $product, quantity: 10);

        expect($line->quantity)->toBe(10);
    });

    it('throws `InvalidDiscountException` when coupon code does not exist', function (): void {
        $this->cartManager->applyCoupon($this->cart, 'FAKE_CODE');
    })->throws(InvalidDiscountException::class);

    it('throws `InsufficientStockException` when updating quantity exceeds stock', function (): void {
        $product = Product::factory()->standard()->create();
        $product->prices()->create([
            'amount' => 10,
            'currency_id' => $this->currency->id,
        ]);
        $product->load('prices');
        $product->mutateStock($this->inventory->id, 5);

        $line = $this->cartManager->add($this->cart, $product, quantity: 2);

        $this->cartManager->update($this->cart, $line->id, ['quantity' => 20]);
    })->throws(InsufficientStockException::class);
})->group('cart', 'cart-manager');
