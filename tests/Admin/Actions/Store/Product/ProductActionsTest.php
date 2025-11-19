<?php

declare(strict_types=1);

use Shopper\Actions\Store\Product\CreateNewVariant;
use Shopper\Actions\Store\Product\SavePricingAction;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Models\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(CreateNewVariant::class, function (): void {
    it('creates new variant with all data', function (): void {
        Inventory::factory()->create(['is_default' => true]);
        /** @var Product $product */
        $product = Product::factory()->create(['type' => ProductType::Variant]);
        $currency = Currency::query()->first();

        $variant = app()->call(CreateNewVariant::class, ['state' => [
            'product_id' => $product->id,
            'name' => 'Test Variant',
            'sku' => 'VAR-001',
            'quantity' => 10,
            'prices' => [
                $currency->id => ['amount' => 1000, 'compare_amount' => 1200],
            ],
        ]]);

        expect($variant)->toBeInstanceOf(ProductVariant::class)
            ->and($variant->sku)->toBe('VAR-001')
            ->and($variant->stock)->toBe(10)
            ->and($variant->prices()->count())->toBe(1);
    });
})->group('actions', 'product');

describe(SavePricingAction::class, function (): void {
    it('saves pricing for model', function (): void {
        $product = Product::factory()->create();
        $currency = Currency::query()->first();

        $action = new SavePricingAction();
        $action(
            pricing: [
                $currency->id => ['amount' => 2500, 'compare_amount' => 3000],
            ],
            model: $product
        );

        $price = $product->prices()->first();

        expect($product->prices()->count())->toBe(1)
            ->and($price->amount)->toBe(2500)
            ->and($price->compare_amount)->toBe(3000);
    });

    it('updates existing pricing', function (): void {
        $product = Product::factory()->create();
        $currency = Currency::query()->first();

        $action = new SavePricingAction();

        $action(
            pricing: [$currency->id => ['amount' => 1000]],
            model: $product
        );

        $action(
            pricing: [$currency->id => ['amount' => 2000]],
            model: $product
        );

        expect($product->prices()->count())->toBe(1)
            ->and($product->prices()->first()->amount)->toBe(2000);
    });
})->group('actions', 'product');
