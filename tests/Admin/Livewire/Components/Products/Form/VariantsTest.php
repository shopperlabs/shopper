<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Livewire\Pages\Product\Variants;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    Livewire::withoutLazyLoading();

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

    it('hides the generate action when the product only has free text attributes', function (): void {
        $material = Attribute::factory()->create(['type' => FieldType::Text]);
        $this->product->options()->attach($material->id, ['attribute_custom_value' => 'Cotton']);

        Livewire::test(Variants::class, ['product' => $this->product->fresh()])
            ->loadTable()
            ->assertActionHidden(TestAction::make('generate')->table());

        $size = Attribute::factory()->create(['type' => FieldType::Checkbox]);
        $this->product->options()->attach($size->id, [
            'attribute_value_id' => AttributeValue::factory()->create(['attribute_id' => $size->id])->id,
        ]);

        Livewire::withoutLazyLoading()->test(Variants::class, ['product' => $this->product->fresh()])
            ->loadTable()
            ->assertActionVisible(TestAction::make('generate')->table());
    });

    it('hides the delete action for users without `products.variants.delete`', function (): void {
        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);

        Livewire::test(Variants::class, ['product' => $this->product])
            ->loadTable()
            ->assertActionHidden(TestAction::make('delete')->table($variant));
    });

    it('hides the bulk delete action for users without `products.variants.delete`', function (): void {
        ProductVariant::factory()->count(3)->create(['product_id' => $this->product->id]);

        Livewire::test(Variants::class, ['product' => $this->product])
            ->loadTable()
            ->assertActionHidden(TestAction::make('delete')->table()->bulk());
    });

    it('allows deleting a variant with `products.variants.delete`', function (): void {
        $this->user->givePermissionTo('products.variants.delete');

        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);

        Livewire::test(Variants::class, ['product' => $this->product])
            ->loadTable()
            ->callAction(TestAction::make('delete')->table($variant));

        expect(ProductVariant::query()->find($variant->id))->toBeNull();
    });

    it('blocks reordering variants for users without `products.variants.edit`', function (): void {
        $a = ProductVariant::factory()->create(['product_id' => $this->product->id, 'position' => 1]);
        $b = ProductVariant::factory()->create(['product_id' => $this->product->id, 'position' => 2]);

        Livewire::test(Variants::class, ['product' => $this->product])
            ->call('reorderTable', [$b->getKey(), $a->getKey()]);

        expect($a->refresh()->position)->toBe(1)
            ->and($b->refresh()->position)->toBe(2);
    });

    it('allows reordering variants for users with `products.variants.edit`', function (): void {
        $this->user->givePermissionTo('products.variants.edit');

        $a = ProductVariant::factory()->create(['product_id' => $this->product->id, 'position' => 1]);
        $b = ProductVariant::factory()->create(['product_id' => $this->product->id, 'position' => 2]);

        Livewire::test(Variants::class, ['product' => $this->product])
            ->call('reorderTable', [$b->getKey(), $a->getKey()]);

        expect($a->refresh()->position)->toBe(2)
            ->and($b->refresh()->position)->toBe(1);
    });
})->group('livewire', 'components', 'products', 'security');
