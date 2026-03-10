<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Products\Form\Files;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->product = Product::factory()->create();
});

describe(Files::class, function (): void {
    it('can render files component', function (): void {
        Livewire::test(Files::class, ['product' => $this->product])
            ->assertOk();
    });

    it('loads product data on mount', function (): void {
        $component = Livewire::test(Files::class, ['product' => $this->product]);

        expect($component->get('product')->id)->toBe($this->product->id);
    });

    it('form has files field', function (): void {
        Livewire::test(Files::class, ['product' => $this->product])
            ->assertFormFieldExists('files');
    });

    it('updates product and dispatches event on store', function (): void {
        Livewire::test(Files::class, ['product' => $this->product])
            ->call('store')
            ->assertDispatched('product.updated');
    });

    it('sends notification after storing files', function (): void {
        Livewire::test(Files::class, ['product' => $this->product])
            ->call('store')
            ->assertNotified();
    });
})->group('livewire', 'components', 'products');
