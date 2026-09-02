<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Settings\Team\Index;
use Shopper\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo(['system.users', 'system.settings']);
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
        $admin->assignRole(config('shopper.admin.roles.admin'));

        Livewire::test(Index::class)
            ->assertOk();
    });

    it('passes roles to view', function (): void {
        $component = Livewire::test(Index::class);
        $roles = $component->viewData('roles');

        expect($roles)->toBeInstanceOf(Illuminate\Support\Collection::class);
    });

    it('can create new role via action', function (): void {
        $initialCount = Role::query()->count();

        Livewire::test(Index::class)
            ->callAction('createRole', [
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Can supervise operations',
            ])
            ->assertHasNoErrors()
            ->assertNotified(__('shopper::notifications.users_roles.role_added'));

        expect(Role::query()->count())->toBe($initialCount + 1)
            ->and(Role::query()->where('name', 'supervisor')->exists())->toBeTrue();
    });

    it('validates required name field when creating role', function (): void {
        Livewire::test(Index::class)
            ->callAction('createRole', [
                'display_name' => 'Manager',
            ])
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('validates unique role name when creating role', function (): void {
        $existingRole = Role::query()->first();

        Livewire::test(Index::class)
            ->callAction('createRole', [
                'name' => $existingRole->name,
            ])
            ->assertHasFormErrors(['name' => 'unique']);
    });

    it('forbids mount for users without `system.users`', function (): void {
        $unauthorized = User::factory()->create();
        $this->actingAs($unauthorized);

        Livewire::test(Index::class)
            ->assertForbidden();
    });

    it('allows optional display_name and description when creating role', function (): void {
        $initialCount = Role::query()->count();

        Livewire::test(Index::class)
            ->callAction('createRole', [
                'name' => 'custom_role_'.uniqid(),
            ])
            ->assertHasNoErrors();

        expect(Role::query()->count())->toBe($initialCount + 1);
    });
})->group('livewire', 'settings', 'team');
