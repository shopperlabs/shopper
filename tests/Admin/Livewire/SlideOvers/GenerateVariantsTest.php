<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Livewire\SlideOvers\GenerateVariants;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\RecordingEngine;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('products.variants.edit', 'products.variants.create');
    $this->actingAs($this->user);

    $this->product = Product::factory()->create(['type' => ProductType::Variant]);
});

describe(GenerateVariants::class, function (): void {
    it('requires products.variants.delete to drop the existing variants', function (): void {
        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);

        Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [
                ['key' => 'red', 'variant_id' => null, 'name' => 'Red', 'sku' => 'NEW-RED', 'price' => 1000, 'stock' => 0, 'values' => []],
            ])
            ->call('generate');

        expect($this->product->variants()->pluck('id')->all())->toBe([$variant->id]);
    });

    it('requires products.variants.create to add variants', function (): void {
        $this->user->revokePermissionTo('products.variants.create');

        Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [
                ['key' => 'red', 'variant_id' => null, 'name' => 'Red', 'sku' => 'NEW-RED', 'price' => 1000, 'stock' => 0, 'values' => []],
            ])
            ->call('generate');

        expect($this->product->variants()->count())->toBe(0);
    });

    it('cannot update a variant of another product', function (): void {
        $foreign = ProductVariant::factory()->create(['product_id' => Product::factory()->create()->id]);

        expect(fn () => Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [
                ['key' => 'red', 'variant_id' => $foreign->id, 'name' => 'Red', 'sku' => 'RED', 'price' => 1, 'stock' => 0, 'values' => []],
            ])
            ->call('generate'))->toThrow(ModelNotFoundException::class);

        expect($foreign->prices()->where('amount', 1)->exists())->toBeFalse();
    });

    it('loads product with options and values', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

        expect($component->get('product'))->not->toBeNull()
            ->and($component->get('product'))->toBe($this->product);
    });

    it('can remove variant from list', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

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

    it('indexes the generated variant skus on the product', function (): void {
        $engine = RecordingEngine::register();
        $product = Product::factory()->publish()->create(['type' => ProductType::Variant]);

        Livewire::test(GenerateVariants::class, ['product' => $product])
            ->set('variants', [
                ['key' => 'red', 'variant_id' => null, 'name' => 'Red', 'sku' => 'AIR-RED', 'price' => 1000, 'stock' => 0, 'values' => []],
                ['key' => 'blue', 'variant_id' => null, 'name' => 'Blue', 'sku' => 'AIR-BLUE', 'price' => 1000, 'stock' => 0, 'values' => []],
            ])
            ->call('generate');

        expect($engine->documents[$product->public_id]['skus'])->toBe(['AIR-RED', 'AIR-BLUE']);
    });

    it('redirects to product variants tab after generating', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

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
                'product' => $this->product,
                'tab' => 'variants',
            ]));
    });

    it('sends notification after generating variants', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

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

    it('assigns incremental positions to generated variants', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

        $component->set('variants', [
            [
                'key' => 'test-key-1',
                'variant_id' => null,
                'name' => 'Variant 1',
                'sku' => 'SKU-POS-1',
                'price' => 1000,
                'stock' => 10,
                'values' => [],
            ],
            [
                'key' => 'test-key-2',
                'variant_id' => null,
                'name' => 'Variant 2',
                'sku' => 'SKU-POS-2',
                'price' => 1000,
                'stock' => 10,
                'values' => [],
            ],
        ]);

        $component->call('generate');

        expect($this->product->variants()->pluck('position', 'sku')->all())
            ->toBe(['SKU-POS-1' => 1, 'SKU-POS-2' => 2]);
    });

    it('drops a compare price that is no longer above the new variant price', function (): void {
        $variantState = [
            'key' => 'test-key-1',
            'variant_id' => null,
            'name' => 'Variant 1',
            'sku' => 'SKU-CMP-1',
            'price' => 3999,
            'stock' => 10,
            'values' => [],
        ];

        Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [$variantState])
            ->call('generate');

        $variant = $this->product->variants()->where('sku', 'SKU-CMP-1')->firstOrFail();
        $variant->prices()->update(['compare_amount' => 4999]);

        $variantState['variant_id'] = $variant->id;
        $variantState['price'] = 5999;

        Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [$variantState])
            ->call('generate');

        $price = $variant->prices()->firstOrFail();

        expect((int) $price->amount)->toBe(5999)
            ->and($price->compare_amount)->toBeNull();
    });
})->group('livewire', 'slideovers', 'products');
