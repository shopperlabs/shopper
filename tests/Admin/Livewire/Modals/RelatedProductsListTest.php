<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\User;
use Shopper\Livewire\Modals\RelatedProductsList;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->product = Product::factory()->create();
});

describe(RelatedProductsList::class, function (): void {
    it('can render related products list modal', function (): void {
        Livewire::test(RelatedProductsList::class, ['productId' => $this->product->id])
            ->assertOk();
    });

    it('initializes with correct product', function (): void {
        $component = Livewire::test(RelatedProductsList::class, ['productId' => $this->product->id]);

        expect($component->get('product')->id)->toBe($this->product->id);
    });

    it('initializes with empty search string', function (): void {
        $component = Livewire::test(RelatedProductsList::class, ['productId' => $this->product->id]);

        expect($component->get('search'))->toBe('');
    });

    it('initializes with empty selected products array', function (): void {
        $component = Livewire::test(RelatedProductsList::class, ['productId' => $this->product->id]);

        expect($component->get('selectedProducts'))->toBe([]);
    });

    it('can update search string', function (): void {
        $component = Livewire::test(RelatedProductsList::class, ['productId' => $this->product->id])
            ->set('search', 'test product');

        expect($component->get('search'))->toBe('test product');
    });

    it('can select products', function (): void {
        $relatedProduct1 = Product::factory()->create();
        $relatedProduct2 = Product::factory()->create();

        $component = Livewire::test(RelatedProductsList::class, ['productId' => $this->product->id])
            ->set('selectedProducts', [$relatedProduct1->id, $relatedProduct2->id]);

        expect($component->get('selectedProducts'))->toBe([$relatedProduct1->id, $relatedProduct2->id]);
    });

    it('can add selected products to related products', function (): void {
        $relatedProduct1 = Product::factory()->create();
        $relatedProduct2 = Product::factory()->create();

        Livewire::test(RelatedProductsList::class, ['productId' => $this->product->id])
            ->set('selectedProducts', [$relatedProduct1->id, $relatedProduct2->id])
            ->call('addSelectedProducts');

        $this->product->refresh();

        expect($this->product->relatedProducts)->toHaveCount(2)
            ->and($this->product->relatedProducts->pluck('id')->toArray())
            ->toContain($relatedProduct1->id, $relatedProduct2->id);
    });

    it('merges new products with existing related products', function (): void {
        $existingProduct = Product::factory()->create();
        $this->product->relatedProducts()->attach($existingProduct->id);

        $newProduct = Product::factory()->create();

        Livewire::test(RelatedProductsList::class, ['productId' => $this->product->id])
            ->set('selectedProducts', [$newProduct->id])
            ->call('addSelectedProducts');

        $this->product->refresh();

        expect($this->product->relatedProducts)->toHaveCount(2)
            ->and($this->product->relatedProducts->pluck('id')->toArray())
            ->toContain($existingProduct->id, $newProduct->id);
    });

    it('redirects to product edit page after adding products', function (): void {
        $relatedProduct = Product::factory()->create();

        Livewire::test(RelatedProductsList::class, ['productId' => $this->product->id])
            ->set('selectedProducts', [$relatedProduct->id])
            ->call('addSelectedProducts')
            ->assertRedirect(route('shopper.products.edit', [
                'product' => $this->product,
                'tab' => 'related',
            ]));
    });

    it('sends notification after adding products', function (): void {
        $relatedProduct = Product::factory()->create();

        Livewire::test(RelatedProductsList::class, ['productId' => $this->product->id])
            ->set('selectedProducts', [$relatedProduct->id])
            ->call('addSelectedProducts')
            ->assertNotified();
    });

    it('can initialize with excluded product ids', function (): void {
        $excludedProduct1 = Product::factory()->create();
        $excludedProduct2 = Product::factory()->create();

        $component = Livewire::test(RelatedProductsList::class, [
            'productId' => $this->product->id,
            'ids' => [$excludedProduct1->id, $excludedProduct2->id],
        ]);

        expect($component->get('exceptProductIds'))->toBe([$excludedProduct1->id, $excludedProduct2->id]);
    });

    it('excludes specified products from available products list', function (): void {
        $availableProduct = Product::factory()->create(['name' => 'Available Product']);
        $excludedProduct = Product::factory()->create(['name' => 'Excluded Product']);

        $component = Livewire::test(RelatedProductsList::class, [
            'productId' => $this->product->id,
            'ids' => [$excludedProduct->id],
        ]);

        $products = $component->instance()->products();

        expect($products->pluck('id')->toArray())->toContain($availableProduct->id)
            ->and($products->pluck('id')->toArray())->not->toContain($excludedProduct->id);
    });

    it('respects exclusion when searching products', function (): void {
        $searchableProduct = Product::factory()->create(['name' => 'Test Product']);
        $excludedProduct = Product::factory()->create(['name' => 'Test Excluded']);

        $component = Livewire::test(RelatedProductsList::class, [
            'productId' => $this->product->id,
            'ids' => [$excludedProduct->id],
        ])
            ->set('search', 'Test');

        $products = $component->instance()->products();

        expect($products->pluck('id')->toArray())->toContain($searchableProduct->id)
            ->and($products->pluck('id')->toArray())->not->toContain($excludedProduct->id);
    });

    it('excludes current product from available products', function (): void {
        $otherProduct = Product::factory()->create(['name' => 'Other Product']);

        $component = Livewire::test(RelatedProductsList::class, [
            'productId' => $this->product->id,
            'ids' => [$this->product->id],
        ]);

        $products = $component->instance()->products();

        expect($products->pluck('id')->toArray())->toContain($otherProduct->id)
            ->and($products->pluck('id')->toArray())->not->toContain($this->product->id);
    });
})->group('livewire', 'modals', 'products');
