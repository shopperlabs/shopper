<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Inventory;
use Shopper\Livewire\SlideOvers\AddVariant;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.product', Product::class);
    config()->set('shopper.models.variant', ProductVariant::class);

    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_products');
    $this->actingAs($this->user);

    $this->product = Product::factory()->create(['type' => ProductType::Variant]);
});

describe(AddVariant::class, function (): void {
    it('requires add_products permission', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(AddVariant::class, ['product' => $this->product])
            ->assertForbidden();
    });

    it('can create a simple variant without options', function (): void {
        Livewire::test(AddVariant::class, ['product' => $this->product])
            ->fillForm([
                'name' => 'Variant Name',
                'sku' => 'SKU-123',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $variant = ProductVariant::query()->first();

        expect(ProductVariant::query()->count())->toBe(1)
            ->and($variant->name)->toBe('Variant Name')
            ->and($variant->sku)->toBe('SKU-123')
            ->and($variant->product_id)->toBe($this->product->id);
    });

    it('validates unique sku', function (): void {
        ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'sku' => 'EXISTING-SKU',
        ]);

        Livewire::test(AddVariant::class, ['product' => $this->product])
            ->fillForm([
                'name' => 'New Variant',
                'sku' => 'EXISTING-SKU',
            ])
            ->call('save')
            ->assertHasFormErrors(['sku' => 'unique']);
    });

    it('validates unique barcode', function (): void {
        ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'barcode' => '1234567890',
        ]);

        Livewire::test(AddVariant::class, ['product' => $this->product])
            ->fillForm([
                'name' => 'New Variant',
                'barcode' => '1234567890',
            ])
            ->call('save')
            ->assertHasFormErrors(['barcode' => 'unique']);
    });

    it('can create variant with quantity and barcode', function (): void {
        Inventory::factory(['is_default' => true])->create();

        Livewire::test(AddVariant::class, ['product' => $this->product])
            ->fillForm([
                'name' => 'Variant with Stock',
                'sku' => 'SKU-STOCK',
                'barcode' => '9876543210',
                'quantity' => 50,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $variant = ProductVariant::query()->first();

        expect($variant->barcode)->toBe('9876543210')
            ->and($variant->stock)->toBe(50);
    });

    it('can create variant with attribute values', function (): void {
        $colorAttribute = Attribute::factory()->create(['name' => 'Color']);
        $redValue = AttributeValue::factory()->create(['attribute_id' => $colorAttribute->id, 'value' => 'Red']);
        AttributeValue::factory()->create(['attribute_id' => $colorAttribute->id, 'value' => 'Blue']);

        $sizeAttribute = Attribute::factory()->create(['name' => 'Size']);
        $smallValue = AttributeValue::factory()->create(['attribute_id' => $sizeAttribute->id, 'value' => 'Small']);
        AttributeValue::factory()->create(['attribute_id' => $sizeAttribute->id, 'value' => 'Large']);

        $this->product->options()->attach([$colorAttribute->id, $sizeAttribute->id]);

        Livewire::test(AddVariant::class, ['product' => $this->product])
            ->fillForm([
                'name' => 'Red Small Variant',
                'sku' => 'RED-SMALL',
                'values' => [
                    $colorAttribute->id => $redValue->id,
                    $sizeAttribute->id => $smallValue->id,
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $variant = ProductVariant::query()->first();

        expect($variant->values->count())->toBe(2)
            ->and($variant->values->pluck('id')->toArray())
            ->toContain($redValue->id, $smallValue->id);
    })->skip();

    it('prevents duplicate variant with same attribute values', function (): void {
        $colorAttribute = Attribute::factory()->create(['name' => 'Color']);
        $redValue = AttributeValue::factory()->create(['attribute_id' => $colorAttribute->id, 'value' => 'Red']);

        $sizeAttribute = Attribute::factory()->create(['name' => 'Size']);
        $smallValue = AttributeValue::factory()->create(['attribute_id' => $sizeAttribute->id, 'value' => 'Small']);

        $this->product->options()->attach([$colorAttribute->id, $sizeAttribute->id]);

        $existingVariant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        $existingVariant->values()->attach([$redValue->id, $smallValue->id]);

        Livewire::test(AddVariant::class, ['product' => $this->product])
            ->fillForm([
                'name' => 'Duplicate Variant',
                'sku' => 'DUP-VAR',
                'values' => [
                    $colorAttribute->id => $redValue->id,
                    $sizeAttribute->id => $smallValue->id,
                ],
            ])
            ->call('save')
            ->assertHasFormErrors();
    })->skip();

    it('redirects to variant page after creation', function (): void {
        Livewire::test(AddVariant::class, ['product' => $this->product])
            ->fillForm([
                'name' => 'Test Variant',
                'sku' => 'TEST-VAR',
            ])
            ->call('save')
            ->assertRedirect();

        $variant = ProductVariant::query()->first();

        expect($variant)->not->toBeNull()
            ->and($variant->name)->toBe('Test Variant');
    });
})->group('livewire', 'slideovers', 'products');
