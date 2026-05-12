<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Products\Form\Seo;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(Seo::class, function (): void {
    it('blocks `store` for users without `products.edit`', function (): void {
        $unauthorized = User::factory()->create();
        $unauthorized->givePermissionTo('products.browse');
        $this->actingAs($unauthorized);

        $product = Product::factory()->create(['seo_title' => 'Original SEO']);

        Livewire::test(Seo::class, ['product' => $product])
            ->fillForm(['seo_title' => 'Tampered SEO'])
            ->call('store')
            ->assertNotDispatched('product.updated');

        expect($product->fresh()->seo_title)->toBe('Original SEO');
    });
})->group('livewire', 'components', 'products', 'security');
