<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\SlideOvers\ProductsPicker;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('products.edit');
    $this->actingAs($this->user);
});

describe(ProductsPicker::class, function (): void {
    it('excludes the given ids from the table query', function (): void {
        $available = Product::factory()->create(['is_visible' => true, 'published_at' => now()->subDay()]);
        $excluded = Product::factory()->create(['is_visible' => true, 'published_at' => now()->subDay()]);

        $query = Livewire::test(ProductsPicker::class, ['exceptIds' => [$excluded->id], 'ability' => 'products.edit'])
            ->instance()
            ->getFilteredTableQuery();

        expect($query->pluck('id')->all())
            ->toContain($available->id)
            ->not->toContain($excluded->id);
    });

    it('dispatches the selected ids on the shared event', function (): void {
        $product = Product::factory()->create(['is_visible' => true, 'published_at' => now()->subDay()]);

        Livewire::test(ProductsPicker::class, ['ability' => 'products.edit'])
            ->callTableBulkAction('add', [$product])
            ->assertDispatched('shopper.products.selected', ids: [$product->id]);
    });

    it('dispatches on the event name passed by the caller instead of the shared one', function (): void {
        $product = Product::factory()->create(['is_visible' => true, 'published_at' => now()->subDay()]);

        Livewire::test(ProductsPicker::class, ['ability' => 'products.edit', 'event' => 'shopper.product.related.selected'])
            ->callTableBulkAction('add', [$product])
            ->assertDispatched('shopper.product.related.selected', ids: [$product->id])
            ->assertNotDispatched('shopper.products.selected');
    });

    it('shows the title passed by the caller', function (): void {
        Livewire::test(ProductsPicker::class, ['ability' => 'products.edit', 'title' => 'Add Similar Products'])
            ->assertSet('panelTitle', 'Add Similar Products');
    });
})->group('livewire', 'slide-overs', 'products');
