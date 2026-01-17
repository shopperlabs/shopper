<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Inventory as InventoryModel;
use Shopper\Livewire\Components\Products\Form\Inventory;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.product', Product::class);

    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->inventory = InventoryModel::factory()->create(['is_default' => true]);
    $this->product = Product::factory()->create([
        'sku' => fake()->unique()->ean8(),
        'barcode' => fake()->unique()->ean13(),
    ]);
});

describe(Inventory::class, function (): void {
    it('can render inventory component', function (): void {
        Livewire::test(Inventory::class, ['product' => $this->product])
            ->assertOk()
            ->assertViewIs('shopper::livewire.components.products.forms.inventory');
    });

    it('loads product data on mount', function (): void {
        $component = Livewire::test(Inventory::class, ['product' => $this->product]);

        expect($component->get('product'))->not->toBeNull()
            ->and($component->get('product')->id)->toBe($this->product->id)
            ->and($component->get('data.sku'))->toBe($this->product->sku);
    });

    it('can update product inventory information', function (): void {
        Livewire::test(Inventory::class, ['product' => $this->product])
            ->fillForm([
                'sku' => 'NEW-SKU',
                'barcode' => '987654321',
                'security_stock' => 5,
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertDispatched('product.updated')
            ->assertNotified(__('shopper::pages/products.notifications.stock_update'));

        $this->product->refresh();
        expect($this->product->sku)->toBe('NEW-SKU')
            ->and($this->product->barcode)->toBe('987654321')
            ->and($this->product->security_stock)->toBe(5);
    });

    it('validates unique sku', function (): void {
        $existingProduct = Product::factory()->create(['sku' => 'EXISTING-SKU']);

        Livewire::test(Inventory::class, ['product' => $this->product])
            ->fillForm([
                'sku' => 'EXISTING-SKU',
            ], 'form')
            ->call('store')
            ->assertHasFormErrors(['sku' => 'unique'], 'form');
    });

    it('validates unique barcode', function (): void {
        $existingProduct = Product::factory()->create(['barcode' => '111222333']);

        Livewire::test(Inventory::class, ['product' => $this->product])
            ->fillForm([
                'barcode' => '111222333',
            ], 'form')
            ->call('store')
            ->assertHasFormErrors(['barcode' => 'unique'], 'form');
    });

    it('can add stock to product', function (): void {
        Livewire::test(Inventory::class, ['product' => $this->product])
            ->callTableAction('stock', data: [
                'inventory' => $this->inventory->id,
                'quantity' => 10,
            ])
            ->assertHasNoTableActionErrors()
            ->assertDispatched('inventory.updated');

        expect($this->product->getStock())->toBe(10);
    });

    it('can remove stock from product', function (): void {
        $this->product->mutateStock($this->inventory->id, 20, ['old_quantity' => 0]);

        Livewire::test(Inventory::class, ['product' => $this->product])
            ->callTableAction('stock', data: [
                'inventory' => $this->inventory->id,
                'quantity' => -5,
            ])
            ->assertHasNoTableActionErrors()
            ->assertDispatched('inventory.updated');

        expect($this->product->getStock())->toBe(15);
    });

    it('displays inventory history table', function (): void {
        $this->product->mutateStock($this->inventory->id, 10, ['old_quantity' => 0, 'event' => 'purchase']);
        $this->product->decreaseStock($this->inventory->id, 3, ['old_quantity' => 10, 'event' => 'sale']);

        Livewire::test(Inventory::class, ['product' => $this->product])
            ->assertCountTableRecords(2);
    });

    it('validates required fields when adding stock', function (): void {
        Livewire::test(Inventory::class, ['product' => $this->product])
            ->callTableAction('stock', data: [])
            ->assertHasTableActionErrors(['inventory' => 'required', 'quantity' => 'required']);
    });
})->group('livewire', 'products');
