<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Contracts\Brand as BrandContract;
use Shopper\Core\Models\Product;

uses(Tests\TestCase::class);

describe(Brand::class, function (): void {
    it('can update brand status', function (): void {
        $brand = Brand::factory()->create(['is_enabled' => false]);

        $brand->updateStatus();

        expect($brand->fresh()->is_enabled)->toBeTrue();
    });

    it('can update brand status to disabled', function (): void {
        $brand = Brand::factory()->create(['is_enabled' => true]);

        $brand->updateStatus(false);

        expect($brand->fresh()->is_enabled)->toBeFalse();
    });

    it('can scope enabled brands', function (): void {
        Brand::factory()->count(3)->create(['is_enabled' => true]);
        Brand::factory()->count(2)->create(['is_enabled' => false]);

        $enabledBrands = resolve(BrandContract::class)::query()->scopes('enabled')->get();

        expect($enabledBrands)->toHaveCount(3);
    });

    it('has products relationship', function (): void {
        $brand = Brand::factory()->create();
        Product::factory()->count(5)->create(['brand_id' => $brand->id]);

        expect($brand->products())->toBeInstanceOf(HasMany::class)
            ->and($brand->products()->count())->toBe(5);
    });

    it('uses correct table name', function (): void {
        $brand = new Brand;

        expect($brand->getTable())->toBe(shopper_table('brands'));
    });

    it('has correct fillable attributes', function (): void {
        $brand = Brand::factory()->create([
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'website' => 'https://example.com',
            'description' => 'Test description',
            'is_enabled' => true,
        ]);

        expect($brand->name)->toBe('Test Brand')
            ->and($brand->slug)->toBe('test-brand')
            ->and($brand->website)->toBe('https://example.com')
            ->and($brand->description)->toBe('Test description')
            ->and($brand->is_enabled)->toBeTrue();
    });

    it('casts is_enabled to boolean', function (): void {
        $brand = Brand::factory()->create(['is_enabled' => 1]);

        expect($brand->is_enabled)->toBeTrue()
            ->and($brand->is_enabled)->toBeBool();
    });

    it('casts metadata to array', function (): void {
        $metadata = ['key' => 'value', 'another_key' => 'another_value'];
        $brand = Brand::factory()->create(['metadata' => $metadata]);

        expect($brand->metadata)->toBeArray()
            ->and($brand->metadata)->toBe($metadata);
    });

    it('can register media collections from HasMedia trait', function (): void {
        $brand = Brand::factory()->create();

        $brand->registerMediaCollections();

        expect($brand->getMediaCollection(config('shopper.media.storage.collection_name')))->not->toBeNull();
    });

    it('implements SpatieHasMedia interface from HasMedia trait', function (): void {
        $brand = Brand::factory()->create();

        expect($brand)->toBeInstanceOf(Spatie\MediaLibrary\HasMedia::class);
    });
})->group('brand', 'models');
