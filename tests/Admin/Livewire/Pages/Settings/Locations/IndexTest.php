<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Livewire\Pages\Settings\Locations\Index;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('inventories.browse');
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

    it('tells how many units the removal deletes before confirming', function (): void {
        $this->user->givePermissionTo('inventories.delete');
        $inventory = Inventory::factory()->create();
        Product::factory()->create()->setStock(newQuantity: 12, inventoryId: $inventory->id);
        Product::factory()->create()->setStock(newQuantity: 3, inventoryId: $inventory->id);

        Livewire::test(Index::class)
            ->mountAction(TestAction::make('remove')->arguments(['id' => $inventory->id]))
            ->assertMountedActionModalSee(__('shopper::pages/settings/global.location.remove_stock', ['count' => 15]));
    });
})->group('livewire', 'settings', 'locations');
