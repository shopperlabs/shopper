<?php

declare(strict_types=1);

use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Product;
use Shopper\Livewire\Modals\CollectionProductsList;
use Shopper\Tests\Admin\Collection\TestCase;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

uses(TestCase::class);

describe(CollectionProductsList::class, function (): void {
    it('can have correct products after search', function (): void {
        Product::factory(['name' => 'Traditionnal Pagne'])->create();
        Product::factory(['name' => 'Veronique Shoe'])->create();
        Product::factory(['name' => 'Monney bag by Laurence'])->create();
        Product::factory(['name' => 'Matanga basket shoes'])->create();
        $collection = Collection::factory(['type' => CollectionType::Manual])->create();

        get(route('shopper.collections.edit', $collection))
            ->assertFound();

        $component = livewire(CollectionProductsList::class, ['collectionId' => $collection->id]);

        $component->assertSuccessful()->set('search', 'Laure');
        $this->assertEquals(1, $component->products->count());

        $component->set('search', 'shoe')
            ->assertSee(['Veronique Shoe', 'Matanga basket shoes'])
            ->set('selectedProducts', [3, 4])
            ->call('addSelectedProducts')
            ->assertDispatched('closeModal');

        $collection->refresh();

        expect($collection->products->count())->toBe(2);
    })->group('collection');
});
