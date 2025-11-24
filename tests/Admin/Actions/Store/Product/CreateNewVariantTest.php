<?php

declare(strict_types=1);

use Shopper\Actions\Store\Product\CreateNewVariant;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.product', Product::class);
    config()->set('shopper.models.variant', ProductVariant::class);

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
