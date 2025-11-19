<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Shopper\Core\Events\Products\Updated;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\User;
use Shopper\Livewire\Components\Products\Form\Edit;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_products');
    $this->actingAs($this->user);

    Event::fake();
});

describe(Edit::class, function (): void {
    it('can update product information', function (): void {
        $product = Product::factory()->create();

        Livewire::test(Edit::class, ['product' => $product])
            ->fillForm([
                'name' => 'Demo product',
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        $product->refresh();

        Event::assertDispatched(Updated::class);

        expect($product->slug)->toBe('demo-product');
    });

    it('ensure that external_id field is invisible on non external product', function (): void {
        $product = Product::factory()->virtual()->create();

        Livewire::test(Edit::class, ['product' => $product])
            ->fillForm()
            ->assertFormFieldIsHidden('external_id');
    });

    it('can view the external id field on external product editing', function (): void {
        $product = Product::factory()->external()->create();

        Livewire::test(Edit::class, ['product' => $product])
            ->fillForm([
                'external_id' => $uuid = fake()->uuid,
            ])
            ->assertFormFieldIsVisible('external_id')
            ->call('store')
            ->assertHasNoFormErrors();

        $product->refresh();

        Event::assertDispatched(Updated::class);

        expect($product->external_id)->toBe($uuid);
    });
})->group('livewire', 'components', 'products');
