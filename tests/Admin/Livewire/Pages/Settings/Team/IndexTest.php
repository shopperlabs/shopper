<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Settings\Team\Index;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render team index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.team.index');
    });

    it('can list administrators in table', function (): void {
        $admin = User::factory()->create();
        $admin->assignRole(config('shopper.core.roles.admin'));

        Livewire::test(Index::class)
            ->assertOk();
    });

    it('passes roles to view', function (): void {
        $component = Livewire::test(Index::class);
        $roles = $component->viewData('roles');

        expect($roles)->toBeInstanceOf(Illuminate\Support\Collection::class);
    });
})->group('livewire', 'settings', 'team');
