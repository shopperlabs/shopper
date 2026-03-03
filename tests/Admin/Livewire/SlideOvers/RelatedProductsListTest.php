<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\SlideOvers\RelatedProductsList;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.product', Product::class);

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_products');
    $this->actingAs($this->user);

    $this->product = Product::factory()->create();
});

describe(RelatedProductsList::class, function (): void {
    it('can render related products list slide-over', function (): void {
        Livewire::test(RelatedProductsList::class, ['product' => $this->product])
            ->assertOk();
    });

    it('initializes with correct product', function (): void {
        $component = Livewire::test(RelatedProductsList::class, ['product' => $this->product]);

        expect($component->get('product')->id)->toBe($this->product->id);
    });

    it('can initialize with excluded product ids', function (): void {
        $excludedProduct1 = Product::factory()->create();
        $excludedProduct2 = Product::factory()->create();

        $component = Livewire::test(RelatedProductsList::class, [
            'product' => $this->product,
            'ids' => [$excludedProduct1->id, $excludedProduct2->id],
        ]);

        expect($component->get('exceptProductIds'))->toBe([$excludedProduct1->id, $excludedProduct2->id]);
    });

    it('can display products in table query', function (): void {
        $availableProduct = Product::factory()->create([
            'name' => 'Available Product',
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);

        $component = Livewire::test(RelatedProductsList::class, ['product' => $this->product]);

        $tableQuery = $component->instance()->getFilteredTableQuery();

        expect($tableQuery->pluck('id')->toArray())->toContain($availableProduct->id);
    });

    it('excludes specified products from table query', function (): void {
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

        $component = Livewire::test(RelatedProductsList::class, [
            'product' => $this->product,
            'ids' => [$excludedProduct->id],
        ]);

        $tableQuery = $component->instance()->getFilteredTableQuery();

        expect($tableQuery->pluck('id')->toArray())
            ->toContain($availableProduct->id)
            ->not->toContain($excludedProduct->id);
    });

    it('can add related products', function (): void {
        $relatedProduct1 = Product::factory()->create([
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);
        $relatedProduct2 = Product::factory()->create([
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->product->relatedProducts()->sync([$relatedProduct1->id, $relatedProduct2->id]);

        $this->product->refresh();

        expect($this->product->relatedProducts)->toHaveCount(2)
            ->and($this->product->relatedProducts->pluck('id')->toArray())
            ->toContain($relatedProduct1->id, $relatedProduct2->id);
    });

    it('merges new products with existing related products', function (): void {
        $existingProduct = Product::factory()->create([
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);
        $this->product->relatedProducts()->attach($existingProduct->id);

        $newProduct = Product::factory()->create([
            'is_visible' => true,
            'published_at' => now()->subDay(),
        ]);

        $currentProducts = $this->product->relatedProducts->pluck('id')->toArray();
        $this->product->relatedProducts()->sync(
            array_merge([$newProduct->id], $currentProducts)
        );

        $this->product->refresh();

        expect($this->product->relatedProducts)->toHaveCount(2)
            ->and($this->product->relatedProducts->pluck('id')->toArray())
            ->toContain($existingProduct->id, $newProduct->id);
    });
})->group('livewire', 'slide-overs', 'products');
