<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Livewire\Pages\Product\Index;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.product', Product::class);

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_products');
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render products index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.products.index')
            ->assertSee(__('shopper::pages/products.menu'));
    });

    it('can list products in table', function (): void {
        Product::factory()->count(3)->create();

        Livewire::test(Index::class)
            ->loadTable()
            ->assertCanSeeTableRecords(resolve(ProductContract::class)::query()->limit(3)->get());
    });
})->group('livewire', 'products');
