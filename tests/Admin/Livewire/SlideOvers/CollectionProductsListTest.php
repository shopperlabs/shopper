<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Livewire\SlideOvers\CollectionProductsList;
use Tests\Core\Stubs\Collection;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.collection', Collection::class);
    config()->set('shopper.models.product', Product::class);

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_collections');
    $this->actingAs($this->user);
});

describe(CollectionProductsList::class, function (): void {
    it('can render collection products list slide-over', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();

        Livewire::test(CollectionProductsList::class, ['collection' => $collection])
            ->assertSuccessful();
    });

    it('initializes with correct collection', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();

        $component = Livewire::test(CollectionProductsList::class, ['collection' => $collection]);

        expect($component->get('collection')->id)->toBe($collection->id);
    });

    it('can initialize with excluded product ids', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();
        $excludedProduct1 = Product::factory()->create();
        $excludedProduct2 = Product::factory()->create();

        $component = Livewire::test(CollectionProductsList::class, [
            'collection' => $collection,
            'exceptProductIds' => [$excludedProduct1->id, $excludedProduct2->id],
        ]);

        expect($component->get('exceptProductIds'))->toBe([$excludedProduct1->id, $excludedProduct2->id]);
    });

    it('can display products in table query', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();
        $availableProduct = Product::factory()->create([
            'name' => 'Available Product',
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);

        $component = Livewire::test(CollectionProductsList::class, ['collection' => $collection]);

        $tableQuery = $component->instance()->getFilteredTableQuery();

        expect($tableQuery->pluck('id')->toArray())->toContain($availableProduct->id);
    });

    it('excludes specified products from table query', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();
        $availableProduct = Product::factory()->create([
            'name' => 'Available Product',
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);
        $excludedProduct = Product::factory()->create([
            'name' => 'Excluded Product',
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);

        $component = Livewire::test(CollectionProductsList::class, [
            'collection' => $collection,
            'exceptProductIds' => [$excludedProduct->id],
        ]);

        $tableQuery = $component->instance()->getFilteredTableQuery();

        expect($tableQuery->pluck('id')->toArray())
            ->toContain($availableProduct->id)
            ->not->toContain($excludedProduct->id);
    });

    it('can add products to collection', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();
        $products = Product::factory()->count(3)->create([
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);

        expect($collection->products)->toHaveCount(0);

        $collection->products()->sync($products->pluck('id')->toArray());

        $collection->refresh();

        $collectionProductIds = $collection->products->pluck('id')->sort()->values()->toArray();
        $expectedProductIds = $products->pluck('id')->sort()->values()->toArray();

        expect($collection->products)->toHaveCount(3)
            ->and($collectionProductIds)
            ->toEqual($expectedProductIds);
    });

    it('merges new products with existing collection products', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();
        $existingProduct = Product::factory()->create([
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);
        $collection->products()->attach($existingProduct->id);

        $newProduct = Product::factory()->create([
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);

        $currentProducts = $collection->products->pluck('id')->toArray();
        $collection->products()->sync(
            array_merge([$newProduct->id], $currentProducts)
        );

        $collection->refresh();

        expect($collection->products)->toHaveCount(2)
            ->and($collection->products->pluck('id')->toArray())
            ->toContain($existingProduct->id, $newProduct->id);
    });
})->group('livewire', 'slide-overs', 'collections');
