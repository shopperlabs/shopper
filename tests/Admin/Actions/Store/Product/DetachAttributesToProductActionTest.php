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

        $action = app(DetachAttributesToProductAction::class);
        $action($attributeProduct, $this->product);

        expect(AttributeProduct::query()->find($attributeProduct->id))->toBeNull();
    });

    it('deletes product attribute and detaches from single variant', function (): void {
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

        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
        ]);
        $variant->values()->attach($attributeValue->id);

        expect($variant->values)->toHaveCount(1);

        $action = app(DetachAttributesToProductAction::class);
        $action($attributeProduct, $this->product);

        expect(AttributeProduct::query()->find($attributeProduct->id))->toBeNull()
            ->and($variant->fresh()->values)->toHaveCount(0);
    });

    it('deletes product attribute and detaches from multiple variants', function (): void {
        $attribute = Attribute::factory()->create(['name' => 'Material']);
        $attributeValue = AttributeValue::factory()->create([
            'attribute_id' => $attribute->id,
            'value' => 'Cotton',
        ]);

        $attributeProduct = AttributeProduct::query()->create([
            'product_id' => $this->product->id,
            'attribute_id' => $attribute->id,
            'attribute_value_id' => $attributeValue->id,
        ]);

        $variant1 = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        $variant2 = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        $variant3 = ProductVariant::factory()->create(['product_id' => $this->product->id]);

        $variant1->values()->attach($attributeValue->id);
        $variant2->values()->attach($attributeValue->id);
        $variant3->values()->attach($attributeValue->id);

        expect($variant1->values)->toHaveCount(1)
            ->and($variant2->values)->toHaveCount(1)
            ->and($variant3->values)->toHaveCount(1);

        $action = app(DetachAttributesToProductAction::class);
        $action($attributeProduct, $this->product);

        expect(AttributeProduct::query()->find($attributeProduct->id))->toBeNull()
            ->and($variant1->fresh()->values)->toHaveCount(0)
            ->and($variant2->fresh()->values)->toHaveCount(0)
            ->and($variant3->fresh()->values)->toHaveCount(0);
    });

    it('runs in database transaction', function (): void {
        $attribute = Attribute::factory()->create();
        $attributeValue = AttributeValue::factory()->create(['attribute_id' => $attribute->id]);

        $attributeProduct = AttributeProduct::query()->create([
            'product_id' => $this->product->id,
            'attribute_id' => $attribute->id,
            'attribute_value_id' => $attributeValue->id,
        ]);

        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        $variant->values()->attach($attributeValue->id);

        expect(AttributeProduct::query()->find($attributeProduct->id))->not->toBeNull()
            ->and($variant->values)->toHaveCount(1);

        $action = app(DetachAttributesToProductAction::class);
        $action($attributeProduct, $this->product);

        expect(AttributeProduct::query()->find($attributeProduct->id))->toBeNull()
            ->and($variant->fresh()->values)->toHaveCount(0);
    });
})->group('products', 'actions', 'attributes');
