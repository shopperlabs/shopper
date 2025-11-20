<?php

declare(strict_types=1);

use Shopper\Actions\Store\Product\AttachedAttributesToProductAction;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->product = Product::factory()->create();
});

describe(AttachedAttributesToProductAction::class, function (): void {
    it('attaches single attribute value to product', function (): void {
        $attribute = Attribute::factory()->create(['name' => 'Color']);
        $attributeValue = AttributeValue::factory()->create([
            'attribute_id' => $attribute->id,
            'value' => 'Red',
        ]);

        $action = app(AttachedAttributesToProductAction::class);
        $action($this->product, [
            $attribute->id => $attributeValue->id,
        ]);

        $attached = AttributeProduct::query()->where('product_id', $this->product->id)
            ->where('attribute_id', $attribute->id)
            ->first();

        expect($attached)->not->toBeNull()
            ->and($attached->attribute_value_id)->toBe($attributeValue->id)
            ->and($this->product->options()->count())->toBe(1);
    });

    it('attaches multiple attribute values to product', function (): void {
        $attribute = Attribute::factory()->create(['name' => 'Size']);
        $value1 = AttributeValue::factory()->create([
            'attribute_id' => $attribute->id,
            'value' => 'Small',
        ]);
        $value2 = AttributeValue::factory()->create([
            'attribute_id' => $attribute->id,
            'value' => 'Medium',
        ]);

        $action = app(AttachedAttributesToProductAction::class);
        $action($this->product, [
            $attribute->id => [$value1->id, $value2->id],
        ]);

        expect($this->product->options()->count())->toBe(2);

        $attached = AttributeProduct::query()->where('product_id', $this->product->id)
            ->where('attribute_id', $attribute->id)
            ->get();

        expect($attached)->toHaveCount(2)
            ->and($attached->pluck('attribute_value_id')->toArray())
            ->toContain($value1->id, $value2->id);
    });

    it('attaches multiple different attributes to product', function (): void {
        $colorAttribute = Attribute::factory()->create(['name' => 'Color']);
        $sizeAttribute = Attribute::factory()->create(['name' => 'Size']);

        $colorValue = AttributeValue::factory()->create([
            'attribute_id' => $colorAttribute->id,
            'value' => 'Blue',
        ]);
        $sizeValue = AttributeValue::factory()->create([
            'attribute_id' => $sizeAttribute->id,
            'value' => 'Large',
        ]);

        $action = app(AttachedAttributesToProductAction::class);
        $action($this->product, [
            $colorAttribute->id => $colorValue->id,
            $sizeAttribute->id => $sizeValue->id,
        ]);

        expect($this->product->options()->count())->toBe(2);

        $colorAttached = AttributeProduct::query()->where('product_id', $this->product->id)
            ->where('attribute_id', $colorAttribute->id)
            ->first();

        $sizeAttached = AttributeProduct::query()->where('product_id', $this->product->id)
            ->where('attribute_id', $sizeAttribute->id)
            ->first();

        expect($colorAttached->attribute_value_id)->toBe($colorValue->id)
            ->and($sizeAttached->attribute_value_id)->toBe($sizeValue->id);
    });

    it('attaches custom value to product', function (): void {
        $attribute = Attribute::factory()->create(['name' => 'Custom Field']);

        $action = app(AttachedAttributesToProductAction::class);
        $action($this->product, [], [
            $attribute->id => 'Custom Value Text',
        ]);

        $attached = AttributeProduct::query()->where('product_id', $this->product->id)
            ->where('attribute_id', $attribute->id)
            ->first();

        expect($attached)->not->toBeNull()
            ->and($attached->attribute_custom_value)->toBe('Custom Value Text')
            ->and($this->product->options()->count())->toBe(1);
    });

    it('attaches both attribute values and custom values to product', function (): void {
        $selectAttribute = Attribute::factory()->create(['name' => 'Size']);
        $textAttribute = Attribute::factory()->create(['name' => 'Description']);

        $selectValue = AttributeValue::factory()->create([
            'attribute_id' => $selectAttribute->id,
            'value' => 'XL',
        ]);

        $action = app(AttachedAttributesToProductAction::class);
        $action($this->product, [
            $selectAttribute->id => $selectValue->id,
        ], [
            $textAttribute->id => 'Custom description text',
        ]);

        expect($this->product->options()->count())->toBe(2);

        $selectAttached = AttributeProduct::query()->where('product_id', $this->product->id)
            ->where('attribute_id', $selectAttribute->id)
            ->first();

        $textAttached = AttributeProduct::query()->where('product_id', $this->product->id)
            ->where('attribute_id', $textAttribute->id)
            ->first();

        expect($selectAttached->attribute_value_id)->toBe($selectValue->id)
            ->and($textAttached->attribute_custom_value)->toBe('Custom description text');
    });

    it('handles empty attributes array', function (): void {
        $action = app(AttachedAttributesToProductAction::class);
        $action($this->product, []);

        expect($this->product->options()->count())->toBe(0);
    });

    it('handles empty custom values array', function (): void {
        $attribute = Attribute::factory()->create();
        $attributeValue = AttributeValue::factory()->create([
            'attribute_id' => $attribute->id,
        ]);

        $action = app(AttachedAttributesToProductAction::class);
        $action($this->product, [
            $attribute->id => $attributeValue->id,
        ], []);

        expect($this->product->options()->count())->toBe(1);
    });
})->group('actions', 'product', 'attributes');
