<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\User;
use Shopper\Livewire\Pages\Settings\Locations\Index;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_inventories');
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render locations index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.locations.index');
    });

    it('passes inventories to view', function (): void {
        Inventory::factory()->count(2)->create();

        $component = Livewire::test(Index::class);
        $inventories = $component->viewData('inventories');

        expect($inventories)->toBeInstanceOf(Illuminate\Database\Eloquent\Collection::class)
            ->and($inventories->count())->toBeGreaterThan(0);
    });
})->group('livewire', 'settings', 'locations');
