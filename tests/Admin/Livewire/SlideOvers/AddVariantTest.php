<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Inventory;
use Shopper\Livewire\SlideOvers\AddVariant;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

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
        $colorAttribute = Attribute::factory()->create([
            'name' => 'Color',
            'type' => FieldType::Select,
        ]);
        $redValue = AttributeValue::factory()->create(['attribute_id' => $colorAttribute->id, 'value' => 'Red']);
        $blueValue = AttributeValue::factory()->create(['attribute_id' => $colorAttribute->id, 'value' => 'Blue']);

        $sizeAttribute = Attribute::factory()->create([
            'name' => 'Size',
            'type' => FieldType::Select,
        ]);
        $smallValue = AttributeValue::factory()->create(['attribute_id' => $sizeAttribute->id, 'value' => 'Small']);
        $largeValue = AttributeValue::factory()->create(['attribute_id' => $sizeAttribute->id, 'value' => 'Large']);

        // Attach attribute values to the product (not just attributes)
        $this->product->options()->attach($colorAttribute->id, ['attribute_value_id' => $redValue->id]);
        $this->product->options()->attach($colorAttribute->id, ['attribute_value_id' => $blueValue->id]);
        $this->product->options()->attach($sizeAttribute->id, ['attribute_value_id' => $smallValue->id]);
        $this->product->options()->attach($sizeAttribute->id, ['attribute_value_id' => $largeValue->id]);

        $product = Product::query()->find($this->product->id);

        Livewire::test(AddVariant::class, ['product' => $product])
            ->set('data.name', 'Red Small Variant')
            ->set('data.sku', 'RED-SMALL')
            ->set('data.values.'.$colorAttribute->id, $redValue->id)
            ->set('data.values.'.$sizeAttribute->id, $smallValue->id)
            ->call('save')
            ->assertHasNoFormErrors();

        $variant = ProductVariant::query()->first();

        expect($variant->values->count())->toBe(2)
            ->and($variant->values->pluck('id')->toArray())
            ->toContain($redValue->id, $smallValue->id);
    });

    it('prevents duplicate variant with same attribute values', function (): void {
        $colorAttribute = Attribute::factory()->create([
            'name' => 'Color',
            'type' => FieldType::Select,
        ]);
        $redValue = AttributeValue::factory()->create(['attribute_id' => $colorAttribute->id, 'value' => 'Red']);

        $sizeAttribute = Attribute::factory()->create([
            'name' => 'Size',
            'type' => FieldType::Select,
        ]);
        $smallValue = AttributeValue::factory()->create(['attribute_id' => $sizeAttribute->id, 'value' => 'Small']);

        // Attach attribute values to the product
        $this->product->options()->attach($colorAttribute->id, ['attribute_value_id' => $redValue->id]);
        $this->product->options()->attach($sizeAttribute->id, ['attribute_value_id' => $smallValue->id]);

        // Create an existing variant with the same attribute values
        $existingVariant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        $existingVariant->values()->attach([$redValue->id, $smallValue->id]);

        $product = Product::query()->find($this->product->id);

        Livewire::test(AddVariant::class, ['product' => $product])
            ->set('data.name', 'Duplicate Variant')
            ->set('data.sku', 'DUP-VAR')
            ->set('data.values.'.$colorAttribute->id, $redValue->id)
            ->set('data.values.'.$sizeAttribute->id, $smallValue->id)
            ->call('save')
            ->assertNoRedirect();

        // The save should have been halted, so only the existing variant should exist
        expect(ProductVariant::query()->count())->toBe(1);
    });

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
