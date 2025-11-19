<?php

declare(strict_types=1);

use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Product;

uses(Tests\TestCase::class);

describe(AttributeProduct::class, function (): void {
    it('belongs to attribute', function (): void {
        $attribute = Attribute::factory()->create();
        $product = Product::factory()->create();
        $attrProduct = AttributeProduct::factory()->create([
            'attribute_id' => $attribute->id,
            'product_id' => $product->id,
        ]);

        expect($attrProduct->attribute->id)->toBe($attribute->id);
    });

    it('belongs to product', function (): void {
        $attribute = Attribute::factory()->create();
        $product = Product::factory()->create();
        $attrProduct = AttributeProduct::factory()->create([
            'attribute_id' => $attribute->id,
            'product_id' => $product->id,
        ]);

        expect($attrProduct->product->id)->toBe($product->id);
    });

    it('belongs to value', function (): void {
        $attribute = Attribute::factory()->create();
        $product = Product::factory()->create();
        $value = AttributeValue::factory()->create(['attribute_id' => $attribute->id]);
        $attrProduct = AttributeProduct::factory()->create([
            'attribute_id' => $attribute->id,
            'product_id' => $product->id,
            'attribute_value_id' => $value->id,
        ]);

        expect($attrProduct->value->id)->toBe($value->id);
    });
})->group('attribute-product', 'models');
