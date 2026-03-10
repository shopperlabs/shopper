<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\Pages\Collection\Index;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo(['browse_collections', 'edit_collections', 'delete_collections']);
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render collections index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.collections.browse')
            ->assertSee(__('shopper::pages/collections.menu'));
    });

    it('can list collections in table', function (): void {
        Collection::factory()->count(3)->create();

        Livewire::test(Index::class)
            ->loadTable()
            ->assertCanSeeTableRecords(Collection::query()->limit(3)->get());
    });

    it('can search collection by name', function (): void {
        $collections = Collection::factory()->count(10)->create();

        $name = $collections->first()->name;

        Livewire::test(Index::class)
            ->loadTable()
            ->searchTable($name)
            ->assertCanSeeTableRecords($collections->where('name', $name))
            ->assertCanNotSeeTableRecords($collections->where('name', '!=', $name));
    });

    it('can display the edit collection page by click on table action', function (): void {
        $collections = Collection::factory()->count(3)->create();
        $collection = $collections->first();

        Livewire::test(Index::class)
            ->assertActionExists(TestAction::make('edit')->table($collection))
            ->callAction(TestAction::make('edit')->table($collection));
    });

    it('can filter collections by type', function (): void {
        Collection::factory()->create(['type' => CollectionType::Manual]);
        Collection::factory()->create(['type' => CollectionType::Auto]);

        Livewire::test(Index::class)
            ->loadTable()
            ->filterTable('type', CollectionType::Manual->value)
            ->assertCanSeeTableRecords(Collection::query()->where('type', CollectionType::Manual)->get())
            ->assertCanNotSeeTableRecords(Collection::query()->where('type', CollectionType::Auto)->get());
    });

    it('can filter collections by zones', function (): void {
        $zone = Zone::factory()->create();
        $collectionWithZone = Collection::factory()->create();
        $collectionWithoutZone = Collection::factory()->create();

        $collectionWithZone->zones()->attach($zone);

        Livewire::test(Index::class)
            ->loadTable()
            ->filterTable('zones', [$zone->id])
            ->assertCanSeeTableRecords([$collectionWithZone])
            ->assertCanNotSeeTableRecords([$collectionWithoutZone]);
    });
})->group('livewire', 'collections');
