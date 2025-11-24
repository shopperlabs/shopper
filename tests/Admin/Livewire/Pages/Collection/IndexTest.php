<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Collection;
use Shopper\Livewire\Pages\Collection\Index;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_collections');
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
            ->assertCanSeeTableRecords(Collection::limit(3)->get());
    });

    it('can search collection by name', function (): void {
        $collections = Collection::factory()->count(10)->create();

        $name = $collections->first()->name;

        Livewire::test(Index::class)
            ->searchTable($name)
            ->assertCanSeeTableRecords($collections->where('name', $name))
            ->assertCanNotSeeTableRecords($collections->where('name', '!=', $name));
    });

    it('can display the edit collection page by click on table action', function (): void {
        $collections = Collection::factory()->count(3)->create();
        $collection = $collections->first();

        Livewire::test(Index::class)
            ->assertTableActionExists('edit')
            ->callTableAction('edit', $collection);
    });
})->group('livewire', 'collections');
