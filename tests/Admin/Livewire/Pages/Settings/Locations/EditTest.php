<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Inventory;
use Shopper\Livewire\Pages\Settings\Locations\Edit;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_inventories');
    $this->actingAs($this->user);

    $this->inventory = Inventory::factory()->create(['name' => 'Main Warehouse']);
});

describe(Edit::class, function (): void {
    it('can render edit location page', function (): void {
        Livewire::test(Edit::class, ['inventory' => $this->inventory])
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.locations.edit');
    });

    it('loads inventory on mount', function (): void {
        $component = Livewire::test(Edit::class, ['inventory' => $this->inventory]);

        expect($component->get('inventory'))->not->toBeNull()
            ->and($component->get('inventory')->id)->toBe($this->inventory->id);
    });

    it('requires edit_inventories permission', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Edit::class, ['inventory' => $this->inventory])
            ->assertForbidden();
    });
})->group('livewire', 'settings');
