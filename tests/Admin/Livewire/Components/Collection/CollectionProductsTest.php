<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Livewire\Components\Collection\CollectionProducts;
use Tests\Core\Stubs\Collection;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_collections');
    $this->actingAs($this->user);
});

describe(CollectionProducts::class, function (): void {
    it('can display products table on manual collection', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();

        Livewire::test(CollectionProducts::class, ['collection' => $collection])
            ->assertSuccessful()
            ->assertCountTableRecords(0)
            ->assertTableActionHidden('rules')
            ->assertTableActionExists('products');
    });

    it('can display rules action on auto collection', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Auto])->create();

        Livewire::test(CollectionProducts::class, ['collection' => $collection])
            ->assertSuccessful()
            ->assertCountTableRecords(0)
            ->assertTableActionHidden('products')
            ->assertTableActionExists('rules');
    });
})->group('livewire', 'components', 'collections');
