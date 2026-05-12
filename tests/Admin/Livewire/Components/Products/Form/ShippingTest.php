<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Products\Form\Shipping;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(Shipping::class, function (): void {
    it('blocks `store` for users without `products.edit`', function (): void {
        $unauthorized = User::factory()->create();
        $unauthorized->givePermissionTo('products.browse');
        $this->actingAs($unauthorized);

        $product = Product::factory()->create(['weight_value' => 100]);

        Livewire::test(Shipping::class, ['product' => $product])
            ->fillForm(['weight_value' => 999])
            ->call('store')
            ->assertNotDispatched('product.updated');

        expect((int) $product->fresh()->weight_value)->toBe(100);
    });
})->group('livewire', 'components', 'products', 'security');
