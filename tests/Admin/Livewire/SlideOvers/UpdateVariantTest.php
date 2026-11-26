<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\AttributeValue;
use Shopper\Livewire\Pages\Product\Attributes;
use Shopper\Livewire\SlideOvers\ChooseProductAttributes;
use Shopper\Livewire\SlideOvers\UpdateVariant;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('products.variants.edit');
    $this->actingAs($this->user);

    $this->product = Product::factory()->create(['type' => ProductType::Variant]);
    $this->variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
});

describe(UpdateVariant::class, function (): void {
    it('loads variant data on mount', function (): void {
        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'name' => 'Original Variant Name',
            'allow_backorder' => true,
        ]);

        $component = Livewire::test(UpdateVariant::class, [
            'product' => $this->product,
            'variant' => $variant,
        ]);

        expect($component->get('variant'))->not->toBeNull()
            ->and($component->get('variant')->id)->toBe($variant->id)
            ->and($component->get('variant')->name)->toBe('Original Variant Name')
            ->and($component->get('variant')->allow_backorder)->toBeTrue();
    });

    it('can update variant basic information', function (): void {
        Livewire::test(UpdateVariant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ])
            ->fillForm([
                'name' => 'Updated Variant Name',
                'allow_backorder' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->variant->refresh();

        expect($this->variant->name)->toBe('Updated Variant Name')
            ->and($this->variant->allow_backorder)->toBeTrue();
    });

    it('can update variant shipping dimensions', function (): void {
        Livewire::test(UpdateVariant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ])
            ->fillForm([
                'name' => 'Variant with Dimensions',
                'weight_value' => 2.5,
                'height_value' => 10.0,
                'width_value' => 5.0,
                'depth_value' => 3.0,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->variant->refresh();

        expect((float) $this->variant->weight_value)->toBe(2.5)
            ->and((float) $this->variant->height_value)->toBe(10.0)
            ->and((float) $this->variant->width_value)->toBe(5.0)
            ->and((float) $this->variant->depth_value)->toBe(3.0);
    });

    it('redirects to variant page after update', function (): void {
        Livewire::test(UpdateVariant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ])
            ->fillForm([
                'name' => 'Updated Name',
            ])
            ->call('save')
            ->assertRedirect();

        $this->variant->refresh();

        expect($this->variant->name)->toBe('Updated Name');
    });

    it('saves a variant that still carries a value removed from the product', function (): void {
        $this->user->givePermissionTo('products.edit', 'attributes.delete');

        $size = Attribute::factory()->create(['name' => 'Size', 'type' => FieldType::Checkbox, 'is_enabled' => true]);
        $small = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'S']);
        $large = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'L']);

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product])
            ->set('data', ['attributes' => [$size->id], 'values' => [$size->id => [$small->id, $large->id]]])
            ->call('store')
            ->assertHasNoErrors();

        ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'S'])->values()->attach($small->id);
        $tShirtL = ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'L']);
        $tShirtL->values()->attach($large->id);

        Livewire::withoutLazyLoading()->test(Attributes::class, ['product' => $this->product])
            ->callAction(TestAction::make('delete')->table(
                AttributeProduct::query()->where('attribute_value_id', $large->id)->firstOrFail()
            ));

        Livewire::test(UpdateVariant::class, ['product' => $this->product->fresh(), 'variant' => $tShirtL])
            ->fillForm(['name' => 'Large tee'])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($tShirtL->refresh()->name)->toBe('Large tee')
            ->and($tShirtL->values()->get()->modelKeys())->toBe([$large->id]);
    });

    it('saves a variant that still carries an option the product no longer offers at all', function (): void {
        $this->user->givePermissionTo('products.edit', 'attributes.delete');

        $color = Attribute::factory()->create(['name' => 'Color', 'type' => FieldType::Checkbox, 'is_enabled' => true]);
        $red = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Red']);
        $blue = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Blue']);
        $size = Attribute::factory()->create(['name' => 'Size', 'type' => FieldType::Checkbox, 'is_enabled' => true]);
        $small = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'S']);

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product])
            ->set('data', ['attributes' => [$color->id, $size->id], 'values' => [$color->id => [$red->id, $blue->id], $size->id => [$small->id]]])
            ->call('store')
            ->assertHasNoErrors();

        $redTee = ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'Red / S']);
        $redTee->values()->attach([$red->id, $small->id]);
        ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'Blue / S'])->values()->attach([$blue->id, $small->id]);

        $attributes = Livewire::withoutLazyLoading()->test(Attributes::class, ['product' => $this->product]);

        AttributeProduct::query()->where('attribute_id', $color->id)->get()
            ->each(fn (AttributeProduct $row) => $attributes->callAction(TestAction::make('delete')->table($row)));

        Livewire::test(UpdateVariant::class, ['product' => $this->product->fresh(), 'variant' => $redTee])
            ->fillForm(['name' => 'Red tee'])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertSet('alert', false);

        expect($redTee->refresh()->name)->toBe('Red tee')
            ->and($redTee->values()->get()->modelKeys())->toEqualCanonicalizing([$red->id, $small->id]);
    });
})->group('livewire', 'slideovers', 'products');
