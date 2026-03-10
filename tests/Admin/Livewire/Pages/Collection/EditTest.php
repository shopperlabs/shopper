<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\Pages\Collection\Edit;
use Tests\Core\Stubs\Collection;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_collections');
    $this->actingAs($this->user);
});

describe(Edit::class, function (): void {
    it('can render collection edit component', function (): void {
        $collection = Collection::factory()->create();

        Livewire::test(Edit::class, ['collection' => $collection])
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.collections.edit');
    });

    it('loads collection data on mount', function (): void {
        $collection = Collection::factory()->create(['name' => 'Test Collection']);

        $component = Livewire::test(Edit::class, ['collection' => $collection]);

        expect($component->get('collection'))->not->toBeNull()
            ->and($component->get('collection')->name)->toBe('Test Collection');
    });

    it('initializes form with collection data', function (): void {
        $collection = Collection::factory()->create();

        $component = Livewire::test(Edit::class, ['collection' => $collection]);

        expect($component->get('data'))->toBeArray();
    });

    it('can edit a collection', function (): void {
        $collection = Collection::factory()->create();
        $newName = 'My manual collection '.fake()->unique()->word();

        Livewire::test(Edit::class, ['collection' => $collection])
            ->fillForm([
                'name' => $newName,
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertNotified(__('shopper::notifications.update', ['item' => __('shopper::pages/collections.single')]));

        expect($collection->refresh()->name)->toBe($newName);
    });

    it('cannot change type of collection on edit form', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();

        Livewire::test(Edit::class, ['collection' => $collection])
            ->fillForm([
                'name' => 'My manual collection',
                'type' => CollectionType::Auto(),
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        expect($collection->refresh()->type)->toBe(CollectionType::Manual);
    });

    it('can update a collection with zones', function (): void {
        $collection = Collection::factory()->create();
        $zones = Zone::factory()->count(2)->create();

        Livewire::test(Edit::class, ['collection' => $collection])
            ->fillForm([
                'zones' => $zones->pluck('id')->toArray(),
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        expect($collection->refresh()->zones)->toHaveCount(2);
    });
})->group('livewire', 'collections');
