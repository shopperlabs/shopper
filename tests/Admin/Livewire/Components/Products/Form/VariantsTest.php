<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Livewire\Components\Products\Form\Variants;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    Livewire::withoutLazyLoading();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_products');
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

    it('hides the delete action for users without `delete_product_variants`', function (): void {
        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);

        Livewire::test(Variants::class, ['product' => $this->product])
            ->loadTable()
            ->assertTableActionHidden('delete', $variant);
    });

    it('hides the bulk delete action for users without `delete_product_variants`', function (): void {
        ProductVariant::factory()->count(3)->create(['product_id' => $this->product->id]);

        Livewire::test(Variants::class, ['product' => $this->product])
            ->loadTable()
            ->assertTableBulkActionHidden('delete');
    });

    it('allows deleting a variant with `delete_product_variants`', function (): void {
        $this->user->givePermissionTo('delete_product_variants');

        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);

        Livewire::test(Variants::class, ['product' => $this->product])
            ->loadTable()
            ->callTableAction('delete', $variant);

        expect(ProductVariant::query()->find($variant->id))->toBeNull();
    });

    it('blocks reordering variants for users without `edit_product_variants`', function (): void {
        $a = ProductVariant::factory()->create(['product_id' => $this->product->id, 'position' => 1]);
        $b = ProductVariant::factory()->create(['product_id' => $this->product->id, 'position' => 2]);

        Livewire::test(Variants::class, ['product' => $this->product])
            ->call('reorderTable', [$b->getKey(), $a->getKey()]);

        expect($a->refresh()->position)->toBe(1)
            ->and($b->refresh()->position)->toBe(2);
    });

    it('allows reordering variants for users with `edit_product_variants`', function (): void {
        $this->user->givePermissionTo('edit_product_variants');

        $a = ProductVariant::factory()->create(['product_id' => $this->product->id, 'position' => 1]);
        $b = ProductVariant::factory()->create(['product_id' => $this->product->id, 'position' => 2]);

        Livewire::test(Variants::class, ['product' => $this->product])
            ->call('reorderTable', [$b->getKey(), $a->getKey()]);

        expect($a->refresh()->position)->toBe(2)
            ->and($b->refresh()->position)->toBe(1);
    });
})->group('livewire', 'components', 'products', 'security');
