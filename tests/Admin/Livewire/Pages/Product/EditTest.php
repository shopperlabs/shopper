<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Shopper\Core\Events\Products\ProductDeleted;
use Shopper\Livewire\Pages\Product\Edit;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.product', Product::class);

    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_products');
    $this->user->givePermissionTo('delete_products');
    $this->actingAs($this->user);
});

describe(Edit::class, function (): void {
    it('can render product edit page', function (): void {
        $product = Product::factory()->create();

        Livewire::test(Edit::class, ['product' => $product])
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.products.edit');
    });

    it('loads product with prices on mount', function (): void {
        $product = Product::factory()->create(['name' => 'Test Product']);

        $component = Livewire::test(Edit::class, ['product' => $product]);

        expect($component->get('product'))->not->toBeNull()
            ->and($component->get('product')->name)->toBe('Test Product');
    });

    it('requires edit_products permission', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        Livewire::test(Edit::class, ['product' => $product])
            ->assertForbidden();
    });

    it('has active tab property', function (): void {
        $product = Product::factory()->create();

        $component = Livewire::test(Edit::class, ['product' => $product]);

        expect($component->get('activeTab'))->toBe('detail');
    });

    it('can delete product', function (): void {
        Event::fake();

        $product = Product::factory()->create();

        Livewire::test(Edit::class, ['product' => $product])
            ->callAction('delete')
            ->assertRedirectToRoute('shopper.products.index')
            ->assertNotified(__('shopper::notifications.delete', ['item' => __('shopper::pages/products.single')]));

        Event::assertDispatched(ProductDeleted::class);
        expect(Product::resolvedQuery()->count())->toBe(0);
    });

    it('delete action requires delete_products permission', function (): void {
        $user = User::factory()->create();
        $user->givePermissionTo('edit_products');
        $this->actingAs($user);

        $product = Product::factory()->create();

        Livewire::test(Edit::class, ['product' => $product])
            ->assertActionHidden('delete');
    });

    it('delete action requires confirmation', function (): void {
        $product = Product::factory()->create();

        Livewire::test(Edit::class, ['product' => $product])
            ->assertActionExists('delete')
            ->assertActionHasIcon('delete', 'untitledui-trash-03');
    });
})->group('livewire', 'products');
