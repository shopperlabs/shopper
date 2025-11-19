<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Permission;
use Shopper\Core\Models\Role;
use Shopper\Core\Models\User;
use Shopper\Livewire\Modals\CreatePermission;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->role = Role::query()->where('name', 'manager')->first();
});

describe(CreatePermission::class, function (): void {
    it('can render create permission modal', function (): void {
        Livewire::test(CreatePermission::class, ['id' => $this->role->id])
            ->assertOk()
            ->assertViewIs('shopper::livewire.modals.create-permission');
    });

    it('can create new permission and assign to role', function (): void {
        $initialCount = Permission::query()->count();

        Livewire::test(CreatePermission::class, ['id' => $this->role->id])
            ->assertFormExists()
            ->fillForm([
                'name' => 'manage_orders',
                'display_name' => 'Manage Orders',
                'description' => 'Can view and manage orders',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertDispatched('permissionAdded')
            ->assertNotified(__('shopper::notifications.users_roles.permission_add'));

        expect(Permission::query()->count())->toBe($initialCount + 1)
            ->and(Permission::query()->where('name', 'manage_orders')->exists())->toBeTrue()
            ->and($this->role->hasPermissionTo('manage_orders'))->toBeTrue();
    });

    it('validates required fields', function (): void {
        Livewire::test(CreatePermission::class, ['id' => $this->role->id])
            ->assertFormExists()
            ->fillForm()
            ->call('save')
            ->assertHasFormErrors(['name' => 'required', 'display_name' => 'required']);
    });

    it('validates unique permission name', function (): void {
        Permission::create(['name' => 'manage_products', 'display_name' => 'Manage Products']);

        Livewire::test(CreatePermission::class, ['id' => $this->role->id])
            ->fillForm([
                'name' => 'manage_products',
                'display_name' => 'Manage Products',
            ])
            ->call('save')
            ->assertHasFormErrors(['name' => 'unique']);
    });

    it('validates max length constraints', function (): void {
        Livewire::test(CreatePermission::class, ['id' => $this->role->id])
            ->fillForm([
                'name' => str_repeat('a', 31),
                'display_name' => str_repeat('b', 76),
            ])
            ->call('save')
            ->assertHasFormErrors(['name' => 'max', 'display_name' => 'max']);
    });

    it('allows optional group_name and description', function (): void {
        $initialCount = Permission::query()->count();

        Livewire::test(CreatePermission::class, ['id' => $this->role->id])
            ->fillForm([
                'name' => 'edit_blog',
                'display_name' => 'Edit Blog',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect(Permission::query()->count())->toBe($initialCount + 1);
    });
})->group('livewire', 'modals');
