<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\User;
use Shopper\Livewire\Modals\CollectionProductsList;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_collections');
    $this->actingAs($this->user);
});

describe(CollectionProductsList::class, function (): void {
    it('can display products modal on manual collection', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual()])->create();

        Livewire::test(CollectionProductsList::class, ['collectionId' => $collection->id])
            ->assertSuccessful()
            ->assertSee(__('shopper::pages/collections.modal.title'));
    });
})->group('livewire', 'modals', 'collections');
