<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Livewire\SlideOvers\AddCollectionForm;
use Tests\Core\Stubs\Collection;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.collection', Collection::class);

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_collections');
    $this->actingAs($this->user);
});

describe(AddCollectionForm::class, function (): void {
    it('can validate required fields on add collection form', function (): void {
        Livewire::test(AddCollectionForm::class)
            ->assertFormExists()
            ->fillForm([])
            ->call('store')
            ->assertHasFormErrors(['name' => 'required', 'type' => 'required']);
    });

    it('can create a collection', function (): void {
        Livewire::test(AddCollectionForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'My manual collection',
                'type' => CollectionType::Manual(),
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertRedirectToRoute(
                'shopper.collections.edit',
                [
                    'collection' => Collection::resolvedQuery()->find(1),
                ]
            );

        expect(Collection::resolvedQuery()->count())->toBe(1);
    });
})->group('livewire', 'slideovers', 'collections');
