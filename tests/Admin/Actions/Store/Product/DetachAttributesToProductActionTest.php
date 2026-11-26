<?php

declare(strict_types=1);

use Shopper\Actions\Store\Product\DetachAttributesToProductAction;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\AttributeValue;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->actingAs($this->user, config('shopper.auth.guard'));

    $this->product = Product::factory()->create();
});

describe(DetachAttributesToProductAction::class, function (): void {
    it('can delete product attribute without variants', function (): void {
        $attribute = Attribute::factory()->create(['name' => 'Size']);
        $attributeValue = AttributeValue::factory()->create([
            'attribute_id' => $attribute->id,
            'value' => 'Large',
        ]);

        $attributeProduct = AttributeProduct::create([
            'product_id' => $this->product->id,
            'attribute_id' => $attribute->id,
            'attribute_value_id' => $attributeValue->id,
        ]);

        app(DetachAttributesToProductAction::class)($attributeProduct);

        expect(AttributeProduct::query()->find($attributeProduct->id))->toBeNull();
    });

    it('keeps the removed value on the variants built from it', function (): void {
        $attribute = Attribute::factory()->create(['name' => 'Color']);
        $attributeValue = AttributeValue::factory()->create([
            'attribute_id' => $attribute->id,
            'value' => 'Blue',
        ]);

        $attributeProduct = AttributeProduct::query()->create([
            'product_id' => $this->product->id,
            'attribute_id' => $attribute->id,
            'attribute_value_id' => $attributeValue->id,
        ]);

        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        $variant->values()->attach($attributeValue->id);

        app(DetachAttributesToProductAction::class)($attributeProduct);

        expect(AttributeProduct::query()->find($attributeProduct->id))->toBeNull()
            ->and($variant->values()->get()->modelKeys())->toBe([$attributeValue->id]);
    });
})->group('products', 'actions', 'attributes');
