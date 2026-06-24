<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Product;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

uses(Tests\Core\TestCase::class);

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

    it('is retrievable through the product `attributeProducts()` relation', function (): void {
        $attribute = Attribute::factory()->create();
        $product = Product::factory()->create();

        AttributeProduct::factory()->count(2)->create([
            'attribute_id' => $attribute->id,
            'product_id' => $product->id,
        ]);

        expect($product->attributeProducts)->toHaveCount(2);
    });

    it('registers a single-file `swatch` media collection without SVG', function (): void {
        $attributeProduct = AttributeProduct::factory()->create();

        $attributeProduct->registerMediaCollections();
        $collection = $attributeProduct->getMediaCollection('swatch');

        expect($collection)->not->toBeNull()
            ->and($collection->singleFile)->toBeTrue()
            ->and($collection->acceptsMimeTypes)->toBe(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
            ->and($collection->acceptsMimeTypes)->not->toContain('image/svg+xml');
    });

    it('implements the `SpatieHasMedia` interface', function (): void {
        expect(AttributeProduct::factory()->create())
            ->toBeInstanceOf(Spatie\MediaLibrary\HasMedia::class);
    });

    it('resolves the `AttributeProduct` contract to the configured model', function (): void {
        expect(resolve(Shopper\Core\Models\Contracts\AttributeProduct::class))
            ->toBeInstanceOf(AttributeProduct::class);
    });

    it('purges its swatch media when deleted', function (): void {
        Storage::fake('public');

        $attributeProduct = AttributeProduct::factory()->create();

        $file = UploadedFile::fake()->image('swatch.png', 10, 10);
        $attributeProduct->addMedia($file)
            ->toMediaCollection('swatch');

        expect(Media::query()->count())->toBe(1);

        $attributeProduct->delete();

        expect(Media::query()->count())->toBe(0);
    });
})->group('attribute-product', 'models');
