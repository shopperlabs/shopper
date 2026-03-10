<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Contracts\Collection as CollectionContract;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\SlideOvers\AddCollectionForm;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_collections');
    $this->actingAs($this->user);
});

describe(AddCollectionForm::class, function (): void {
    it('can validate required fields on add collection form', function (): void {
        Livewire::test(AddCollectionForm::class)
            ->fillForm()
            ->call('store')
            ->assertHasFormErrors(['name' => 'required', 'type' => 'required']);
    });

    it('can create a collection', function (): void {
        Livewire::test(AddCollectionForm::class)
            ->fillForm([
                'name' => 'My manual collection',
                'type' => CollectionType::Manual(),
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertRedirectToRoute(
                'shopper.collections.edit',
                [
                    'collection' => resolve(CollectionContract::class)::query()->first(),
                ]
            );

        expect(resolve(CollectionContract::class)::query()->count())->toBe(1);
    });

    it('can create a collection with zones', function (): void {
        $zones = Zone::factory()->count(2)->create();

        Livewire::test(AddCollectionForm::class)
            ->fillForm([
                'name' => 'Zoned collection',
                'type' => CollectionType::Manual(),
                'zones' => $zones->pluck('id')->toArray(),
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        $collection = resolve(CollectionContract::class)::query()->first();

        expect($collection)->not->toBeNull()
            ->and($collection->zones)->toHaveCount(2);
    });
})->group('livewire', 'slideovers', 'collections');
