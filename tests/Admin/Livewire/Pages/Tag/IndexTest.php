<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Tag\Index;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('tags.browse');
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('hides the create action for users without `tags.create`', function (): void {
        Livewire::test(Index::class)
            ->assertActionHidden('create');
    });

    it('shows the create action for users with `tags.create`', function (): void {
        $this->user->givePermissionTo('tags.create');

        Livewire::test(Index::class)
            ->assertActionVisible('create');
    });
})->group('livewire', 'products', 'security');
