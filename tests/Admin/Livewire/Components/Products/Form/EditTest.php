<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Shopper\Core\Events\Products\ProductUpdated;
use Shopper\Core\Models\Supplier;
use Shopper\Enum\FeatureState;
use Shopper\Livewire\Pages\Product\Overview;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('products.edit');
    $this->actingAs($this->user);

    Event::fake();
});

describe(Overview::class, function (): void {
    it('can update product information', function (): void {
        $product = Product::factory()->standard()->create();

        Livewire::test(Overview::class, ['product' => $product])
            ->fillForm([
                'name' => 'Demo product',
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        $product->refresh();

        Event::assertDispatched(ProductUpdated::class);

        expect($product->slug)->toBe('demo-product');
    });

    it('ensure that external_id field is invisible on non external product', function (): void {
        $product = Product::factory()->virtual()->create();

        Livewire::test(Overview::class, ['product' => $product])
            ->fillForm()
            ->assertFormFieldIsHidden('external_id');
    });

    it('blocks `store` for users without `products.edit`', function (): void {
        $unauthorized = User::factory()->create();
        $unauthorized->givePermissionTo('products.browse');
        $this->actingAs($unauthorized);

        $product = Product::factory()->standard()->create(['name' => 'Original']);

        Livewire::test(Overview::class, ['product' => $product])
            ->fillForm(['name' => 'Tampered'])
            ->call('store');

        expect($product->fresh()->name)->toBe('Original');
        Event::assertNotDispatched(ProductUpdated::class);
    });

    it('can view the external id field on external product editing', function (): void {
        config()->set('shopper.features.supplier', FeatureState::Enabled);

        $supplier = Supplier::factory()->create(['is_enabled' => true]);
        $product = Product::factory()->external()->create();

        Livewire::test(Overview::class, ['product' => $product])
            ->fillForm([
                'external_id' => $uuid = fake()->uuid,
                'supplier_id' => $supplier->id,
            ])
            ->assertFormFieldIsVisible('external_id')
            ->call('store')
            ->assertHasNoFormErrors();

        $product->refresh();

        Event::assertDispatched(ProductUpdated::class);

        expect($product->external_id)->toBe($uuid)
            ->and($product->supplier_id)->toBe($supplier->id);
    });
})->group('livewire', 'components', 'products');
