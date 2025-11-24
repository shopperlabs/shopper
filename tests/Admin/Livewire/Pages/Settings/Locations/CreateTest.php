<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Settings\Locations\Create;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_inventories');
    $this->actingAs($this->user);
});

describe(Create::class, function (): void {
    it('can render create location page', function (): void {
        Livewire::test(Create::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.locations.create');
    });

    it('requires add_inventories permission', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Create::class)
            ->assertForbidden();
    });
})->group('livewire', 'settings');
