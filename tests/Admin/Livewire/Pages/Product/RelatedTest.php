<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Product\Related;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('products.edit');
    $this->actingAs($this->user);

    $this->product = Product::factory()->create();
});

describe(Related::class, function (): void {
    it('attaches the products selected from the picker without dropping existing ones', function (): void {
        $existing = Product::factory()->create();
        $this->product->relatedProducts()->attach($existing->id);

        $added = Product::factory()->create();

        Livewire::test(Related::class, ['product' => $this->product])
            ->dispatch('shopper.product.related.selected', ids: [$added->id]);

        expect($this->product->relatedProducts()->pluck('id')->all())
            ->toContain($existing->id, $added->id)
            ->toHaveCount(2);
    });

    it('excludes the product itself and its related products from the picker', function (): void {
        $related = Product::factory()->create();
        $this->product->relatedProducts()->attach($related->id);

        $exceptIds = Livewire::test(Related::class, ['product' => $this->product])
            ->instance()
            ->productsIds();

        expect($exceptIds)->toContain($this->product->id, $related->id);
    });
})->group('livewire', 'products');
