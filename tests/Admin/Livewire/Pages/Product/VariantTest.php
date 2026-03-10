<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Livewire\Pages\Product\Variant;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_products');
    $this->actingAs($this->user);

    $this->product = Product::factory()->create(['type' => ProductType::Variant]);
    $this->variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
});

describe(Variant::class, function (): void {
    it('can render variant page', function (): void {
        Livewire::test(Variant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ])
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.products.variant');
    });

    it('loads product and variant with relations on mount', function (): void {
        $component = Livewire::test(Variant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ]);

        expect($component->get('product'))->not->toBeNull()
            ->and($component->get('variant'))->not->toBeNull()
            ->and($component->get('product')->id)->toBe($this->product->id)
            ->and($component->get('variant')->id)->toBe($this->variant->id);
    });

    it('requires edit_products permission', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Variant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ])
            ->assertForbidden();
    });

    it('can update variant stock information', function (): void {
        Livewire::test(Variant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ])
            ->callAction('updateStock', data: [
                'sku' => 'NEW-SKU-123',
                'barcode' => '1234567890',
            ])
            ->assertHasNoActionErrors()
            ->assertNotified(__('shopper::pages/products.notifications.variation_update'));

        $this->variant->refresh();
        expect($this->variant->sku)->toBe('NEW-SKU-123')
            ->and($this->variant->barcode)->toBe('1234567890');
    });

    it('validates unique sku when updating stock', function (): void {
        $existingVariant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'sku' => 'EXISTING-SKU',
        ]);

        Livewire::test(Variant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ])
            ->callAction('updateStock', data: [
                'sku' => 'EXISTING-SKU',
            ])
            ->assertHasActionErrors(['sku' => 'unique']);
    });

    it('validates unique barcode when updating stock', function (): void {
        ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'barcode' => '9999999999',
        ]);

        Livewire::test(Variant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ])
            ->callAction('updateStock', data: [
                'barcode' => '9999999999',
            ])
            ->assertHasActionErrors(['barcode' => 'unique']);
    });

    it('has media action', function (): void {
        Livewire::test(Variant::class, [
            'product' => $this->product,
            'variant' => $this->variant,
        ])
            ->assertActionExists('media');
    });
})->group('livewire', 'products');
