<?php

declare(strict_types=1);

use Shopper\Core\Models\Category;
use Shopper\Core\Models\Contracts\Category as CategoryContract;
use Shopper\Core\Models\Product;

uses(Tests\TestCase::class);

describe(Category::class, function (): void {
    it('has enabled scope', function (): void {
        Category::factory()->create(['is_enabled' => false, 'slug' => 'disabled-category']);
        $enabled = Category::factory()->create(['is_enabled' => true, 'slug' => 'enabled-category']);

        $result = resolve(CategoryContract::class)::query()
            ->scopes('enabled')
            ->where('id', $enabled->id)
            ->first();

        expect($result->id)->toBe($enabled->id)
            ->and($result->is_enabled)->toBeTrue();
    });

    it('updates status', function (): void {
        $category = Category::factory()->create(['is_enabled' => false, 'slug' => 'test-category']);

        $category->updateStatus();

        expect($category->fresh()->is_enabled)->toBeTrue();
    });

    it('has parent-child relationship', function (): void {
        $parent = Category::factory()->create(['slug' => 'parent-category']);
        $child = Category::factory()->create(['parent_id' => $parent->id, 'slug' => 'child-category']);

        expect($child->parent->id)->toBe($parent->id);
    });

    it('builds label option name with parent', function (): void {
        $parent = Category::factory()->create(['name' => 'Electronics', 'slug' => 'electronics']);
        $child = Category::factory()->create(['name' => 'Phones', 'parent_id' => $parent->id, 'slug' => 'phones']);

        expect($child->getLabelOptionName())->toContain('Electronics')
            ->and($child->getLabelOptionName())->toContain('Phones');
    });

    it('has products relationship', function (): void {
        $category = Category::factory()->create(['slug' => 'test-category']);
        $product = Product::factory()->create();
        $product->categories()->attach($category);

        expect($category->products())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphToMany::class)
            ->and($category->products()->count())->toBe(1);
    });

    it('can register media collections from HasMedia trait', function (): void {
        $category = Category::factory()->create(['slug' => 'test-category']);

        $category->registerMediaCollections();

        expect($category->getMediaCollection(config('shopper.media.storage.collection_name')))->not->toBeNull();
    });

    it('implements SpatieHasMedia interface from HasMedia trait', function (): void {
        $category = Category::factory()->create(['slug' => 'test-category']);

        expect($category)->toBeInstanceOf(Spatie\MediaLibrary\HasMedia::class);
    });

    it('returns custom paths configuration', function (): void {
        $category = Category::factory()->create(['slug' => 'test-category']);

        $paths = $category->getCustomPaths();

        expect($paths)->toBeArray()
            ->and($paths[0]['name'])->toBe('slug_path')
            ->and($paths[0]['column'])->toBe('slug')
            ->and($paths[0]['separator'])->toBe('/');
    });

    it('has descendant categories relationship', function (): void {
        $parent = Category::factory()->create(['slug' => 'parent']);
        $child1 = Category::factory()->create(['parent_id' => $parent->id, 'slug' => 'child-1']);
        Category::factory()->create(['parent_id' => $parent->id, 'slug' => 'child-2']);
        Category::factory()->create(['parent_id' => $child1->id, 'slug' => 'grandchild']);

        expect($parent->descendantCategories())->toBeInstanceOf(Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants::class)
            ->and($parent->descendantCategories()->count())->toBe(3);
    });

    it('updates status to disabled', function (): void {
        $category = Category::factory()->create(['is_enabled' => true, 'slug' => 'test-category']);

        $category->updateStatus(false);

        expect($category->fresh()->is_enabled)->toBeFalse();
    });

    it('builds nested label option name with multiple levels', function (): void {
        $grandparent = Category::factory()->create(['name' => 'Electronics', 'slug' => 'electronics']);
        $parent = Category::factory()->create(['name' => 'Phones', 'parent_id' => $grandparent->id, 'slug' => 'phones']);
        $child = Category::factory()->create(['name' => 'iPhone', 'parent_id' => $parent->id, 'slug' => 'iphone']);

        expect($child->getLabelOptionName())->toBe('Electronics / Phones / iPhone');
    });

    it('casts is_enabled to boolean', function (): void {
        $category = Category::factory()->create(['is_enabled' => 1, 'slug' => 'test-category']);

        expect($category->is_enabled)->toBeTrue()
            ->and($category->is_enabled)->toBeBool();
    });

    it('casts metadata to array', function (): void {
        $metadata = ['key' => 'value', 'another_key' => 'another_value'];
        $category = Category::factory()->create(['metadata' => $metadata, 'slug' => 'test-category']);

        expect($category->metadata)->toBeArray()
            ->and($category->metadata)->toBe($metadata);
    });
})->group('category', 'models');
