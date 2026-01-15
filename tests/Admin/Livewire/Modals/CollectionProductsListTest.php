<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Livewire\Modals\CollectionProductsList;
use Tests\Core\Stubs\Collection;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.collection', Collection::class);
    config()->set('shopper.models.product', Product::class);

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_collections');
    $this->actingAs($this->user);
});

describe(CollectionProductsList::class, function (): void {
    it('can display products modal on manual collection', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();

        Livewire::test(CollectionProductsList::class, ['collection' => $collection])
            ->assertSuccessful()
            ->assertSee(__('shopper::pages/collections.modal.title'));
    });

    it('can add products to manual collection', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();
        $products = Product::factory()->count(3)->create();

        expect($collection->products)->toHaveCount(0);

        Livewire::test(CollectionProductsList::class, ['collection' => $collection])
            ->set('selectedProducts', $products->pluck('id')->toArray())
            ->call('addSelectedProducts')
            ->assertDispatched('collection.add.product');

        $collection->refresh();

        expect($collection->products)->toHaveCount(3)
            ->and($collection->products->pluck('id')->toArray())
            ->toEqual($products->pluck('id')->toArray());
    });
})->group('livewire', 'modals', 'collections');
