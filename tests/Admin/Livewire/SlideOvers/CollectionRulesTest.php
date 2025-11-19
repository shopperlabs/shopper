<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\User;
use Shopper\Livewire\SlideOvers\CollectionRules;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_collections');
    $this->actingAs($this->user);
});

describe(CollectionRules::class, function (): void {
    it('can save rules on auto collection', function (): void {
        $collection = Collection::factory(['type' => CollectionType::Auto])->create();

        Livewire::test(CollectionRules::class, ['collection' => $collection])
            ->assertSuccessful()
            ->assertFormExists()
            ->fillForm()
            ->call('store')
            ->assertHasNoFormErrors();
    });
})->group('livewire', 'slideovers', 'collections');
