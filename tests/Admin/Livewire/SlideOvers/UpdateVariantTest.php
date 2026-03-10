<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Livewire\SlideOvers\UpdateVariant;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_product_variants');
    $this->actingAs($this->user);

    $this->product = Product::factory()->create(['type' => ProductType::Variant]);
    $this->variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
});

describe(UpdateVariant::class, function (): void {
    it('can render update variant slideover', function (): void {
        Livewire::test(UpdateVariant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ])
            ->assertOk();
    });

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
})->group('livewire', 'slideovers', 'products');
