<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\User;
use Shopper\Livewire\SlideOvers\GenerateVariants;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->product = Product::factory()->create(['type' => ProductType::Variant]);
});

describe(GenerateVariants::class, function (): void {
    it('can render generate variants slideover', function (): void {
        Livewire::test(GenerateVariants::class, ['productId' => $this->product->id])
            ->assertOk();
    });

    it('loads product with options and values', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['productId' => $this->product->id]);

        expect($component->get('product'))->not->toBeNull()
            ->and($component->get('product')->id)->toBe($this->product->id);
    });

    it('initializes variants array on mount', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['productId' => $this->product->id]);

        expect($component->get('variants'))->toBeArray();
    });

    it('can remove variant from list', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['productId' => $this->product->id]);

        $component->set('variants', [
            0 => [
                'key' => 'test-key-1',
                'variant_id' => null,
                'name' => 'Variant 1',
                'sku' => 'SKU-1',
                'price' => 1000,
                'stock' => 10,
                'values' => [1, 2],
            ],
            1 => [
                'key' => 'test-key-2',
                'variant_id' => null,
                'name' => 'Variant 2',
                'sku' => 'SKU-2',
                'price' => 2000,
                'stock' => 20,
                'values' => [3, 4],
            ],
        ]);

        $component->call('removeVariant', 0);

        expect($component->get('variants'))->not->toHaveKey(0)
            ->and($component->get('variants'))->toHaveKey(1);
    });

    it('redirects to product variants tab after generating', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['productId' => $this->product->id]);

        $component->set('variants', [
            [
                'key' => 'test-key',
                'variant_id' => null,
                'name' => 'Test Variant',
                'sku' => 'TEST-SKU',
                'price' => 1000,
                'stock' => 10,
                'values' => [],
            ],
        ]);

        $component->call('generate')
            ->assertRedirect(route('shopper.products.edit', [
                'product' => $this->product->id,
                'tab' => 'variants',
            ]));
    });

    it('sends notification after generating variants', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['productId' => $this->product->id]);

        $component->set('variants', [
            [
                'key' => 'test-key',
                'variant_id' => null,
                'name' => 'Test Variant',
                'sku' => 'TEST-SKU',
                'price' => 1000,
                'stock' => 10,
                'values' => [],
            ],
        ]);

        $component->call('generate')
            ->assertNotified();
    });
})->group('livewire', 'slideovers', 'products');
