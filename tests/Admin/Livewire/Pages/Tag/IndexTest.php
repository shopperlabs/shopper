<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Tag\Index;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_tags');
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('hides the create action for users without `add_tags`', function (): void {
        Livewire::test(Index::class)
            ->assertActionHidden('create');
    });

    it('shows the create action for users with `add_tags`', function (): void {
        $this->user->givePermissionTo('add_tags');

        Livewire::test(Index::class)
            ->assertActionVisible('create');
    });
})->group('livewire', 'products', 'security');
