<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Products\DeleteAction;
use Shopper\Livewire\Pages\Product\Overview;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('products.edit', 'products.delete');
    $this->actingAs($this->user);
});

it('renders the product overview page', function (): void {
    $product = Product::factory()->create(['name' => 'Wavy Twist']);

    Livewire::test(Overview::class, ['product' => $product])
        ->assertOk();
})->group('products');

it('renders the delete action component', function (): void {
    $product = Product::factory()->create();

    Livewire::test(DeleteAction::class, ['product' => $product])
        ->assertOk()
        ->assertActionExists('delete');
})->group('products');
