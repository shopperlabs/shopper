<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\User;
use Shopper\Livewire\Components\Products\Form\Variants;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->product = Product::factory()->create(['type' => ProductType::Variant]);
});

describe(Variants::class, function (): void {
    it('can render variants component', function (): void {
        Livewire::test(Variants::class, ['product' => $this->product])
            ->assertOk();
    });

    it('renders placeholder view', function (): void {
        $component = new Variants;
        $component->product = $this->product;

        $placeholder = $component->placeholder();

        expect($placeholder)->toBeInstanceOf(Illuminate\Contracts\View\View::class);
    });

    it('has product property', function (): void {
        $component = Livewire::test(Variants::class, ['product' => $this->product]);

        expect($component->get('product')->id)->toBe($this->product->id);
    });
})->group('livewire', 'components', 'products');
