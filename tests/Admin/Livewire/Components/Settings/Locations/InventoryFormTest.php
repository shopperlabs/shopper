<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Inventory;
use Shopper\Livewire\Components\Settings\Locations\InventoryForm;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(InventoryForm::class, function (): void {
    it('blocks `store` when editing for users without `edit_inventories`', function (): void {
        $unauthorized = User::factory()->create();
        $unauthorized->givePermissionTo('browse_inventories');
        $this->actingAs($unauthorized);

        $inventory = Inventory::factory()->create(['name' => 'Original Location']);

        Livewire::test(InventoryForm::class, ['inventory' => $inventory])
            ->fillForm(['name' => 'Tampered Location'])
            ->call('store');

        expect($inventory->fresh()->name)->toBe('Original Location');
    });

    it('blocks `store` when creating for users without `add_inventories`', function (): void {
        $unauthorized = User::factory()->create();
        $unauthorized->givePermissionTo('browse_inventories');
        $this->actingAs($unauthorized);

        $countBefore = Inventory::query()->count();

        Livewire::test(InventoryForm::class, ['inventory' => new Inventory])
            ->fillForm(['name' => 'Sneaky Location', 'email' => 'a@b.c'])
            ->call('store');

        expect(Inventory::query()->count())->toBe($countBefore);
    });
})->group('livewire', 'components', 'settings', 'security');
