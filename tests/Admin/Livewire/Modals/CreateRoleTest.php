<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Role;
use Shopper\Livewire\Modals\CreateRole;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(CreateRole::class, function (): void {
    it('can render create role modal', function (): void {
        Livewire::test(CreateRole::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.modals.create-role');
    });

    it('can create new role', function (): void {
        $initialCount = Role::query()->count();

        Livewire::test(CreateRole::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Can supervise operations',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertDispatched('teamUpdate')
            ->assertNotified(__('shopper::notifications.users_roles.role_added'));

        expect(Role::query()->count())->toBe($initialCount + 1)
            ->and(Role::query()->where('name', 'supervisor')->exists())->toBeTrue();
    });

    it('validates required name field', function (): void {
        Livewire::test(CreateRole::class)
            ->assertFormExists()
            ->fillForm([
                'display_name' => 'Manager',
            ])
            ->call('save')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('validates unique role name', function (): void {
        $existingRole = Role::query()->first();

        Livewire::test(CreateRole::class)
            ->fillForm([
                'name' => $existingRole->name,
            ])
            ->call('save')
            ->assertHasFormErrors(['name' => 'unique']);
    });

    it('allows optional display_name and description', function (): void {
        $initialCount = Role::query()->count();

        Livewire::test(CreateRole::class)
            ->fillForm([
                'name' => 'custom_role_'.uniqid(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect(Role::query()->count())->toBe($initialCount + 1);
    });
})->group('livewire', 'modals');
