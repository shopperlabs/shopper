<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Livewire\SlideOvers\GenerateVariants;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('products.variants.edit');
    $this->actingAs($this->user);

    $this->product = Product::factory()->create(['type' => ProductType::Variant]);
});

describe(GenerateVariants::class, function (): void {
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
