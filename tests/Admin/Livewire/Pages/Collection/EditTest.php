<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\User;
use Shopper\Livewire\Pages\Collection\Edit;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_collections');
    $this->actingAs($this->user);
});

describe(Edit::class, function (): void {
    it('can render collection edit component', function (): void {
        $collection = Collection::factory()->create();

        Livewire::test(Edit::class, ['collection' => $collection->id])
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.collections.edit');
    });

    it('loads collection data on mount', function (): void {
        $collection = Collection::factory()->create(['name' => 'Test Collection']);

        $component = Livewire::test(Edit::class, ['collection' => $collection->id]);

        expect($component->get('collection'))->not->toBeNull()
            ->and($component->get('collection')->name)->toBe('Test Collection');
    });

    it('initializes form with collection data', function (): void {
        $collection = Collection::factory()->create();

        $component = Livewire::test(Edit::class, ['collection' => $collection->id]);

        expect($component->get('data'))->toBeArray();
    });

    it('can edit a collection', function (): void {
        $collection = Collection::factory()->create();

        Livewire::test(Edit::class, ['collection' => $collection->id])
            ->fillForm([
                'name' => 'My manual collection',
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertNotified(__('shopper::notifications.update', ['item' => __('shopper::pages/collections.single')]));

        expect($collection->refresh()->name)->toBe('My manual collection');
    });

    it('cannot change type of collection on edit form', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual()])->create();

        Livewire::test(Edit::class, ['collection' => $collection->id])
            ->fillForm([
                'name' => 'My manual collection',
                'type' => CollectionType::Auto(),
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        expect($collection->refresh()->type)->toBe(CollectionType::Manual);
    });
})->group('livewire', 'collections');
