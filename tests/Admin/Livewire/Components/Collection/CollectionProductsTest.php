<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Livewire\Components\Collection\CollectionProducts;
use Tests\Core\Stubs\Collection;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('collections.edit');
    $this->actingAs($this->user);
});

describe(CollectionProducts::class, function (): void {
    it('can display products table on manual collection', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();

        Livewire::test(CollectionProducts::class, ['collection' => $collection])
            ->assertSuccessful()
            ->assertCountTableRecords(0)
            ->assertActionHidden(TestAction::make('rules')->table())
            ->assertActionExists(TestAction::make('products')->table());
    });

    it('can display rules action on auto collection', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Auto])->create();

        Livewire::test(CollectionProducts::class, ['collection' => $collection])
            ->assertSuccessful()
            ->assertCountTableRecords(0)
            ->assertActionHidden(TestAction::make('products')->table())
            ->assertActionExists(TestAction::make('rules')->table());
    });
})->group('livewire', 'components', 'collections');
