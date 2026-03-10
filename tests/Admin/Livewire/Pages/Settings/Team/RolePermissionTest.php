<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Settings\Team\RolePermission;
use Shopper\Models\Permission;
use Shopper\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('view_users');
    $this->actingAs($this->user);

    $this->role = Role::create([
        'name' => 'editor',
        'display_name' => 'Editor',
        'description' => 'Can edit content',
        'can_be_removed' => true,
    ]);
});

describe(RolePermission::class, function (): void {
    it('can render role permission page', function (): void {
        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.team.role');
    });

    it('loads role data on mount', function (): void {
        $component = Livewire::test(RolePermission::class, ['role' => $this->role]);

        expect($component->get('data.name'))->toBe('editor')
            ->and($component->get('data.display_name'))->toBe('Editor')
            ->and($component->get('data.description'))->toBe('Can edit content');
    });

    it('can update role name', function (): void {
        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->set('data.name', 'blogger')
            ->set('data.display_name', 'Blogger')
            ->call('save');

        $this->role->refresh();

        expect($this->role->name)->toBe('blogger')
            ->and($this->role->display_name)->toBe('Blogger');
    });

    it('auto-generates display name from name', function (): void {
        $component = Livewire::test(RolePermission::class, ['role' => $this->role])
            ->set('data.name', 'content manager')
            ->set('data.display_name', 'Content Manager');

        expect($component->get('data.display_name'))->toBe('Content Manager');
    });

    it('validates required name field', function (): void {
        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->set('data.name', '')
            ->call('save')
            ->assertHasErrors(['data.name']);
    });

    it('validates required display name field', function (): void {
        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->set('data.display_name', '')
            ->call('save')
            ->assertHasErrors(['data.display_name']);
    });

    it('sends notification after successful save', function (): void {
        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->set('data.name', 'blogger')
            ->set('data.display_name', 'Blogger')
            ->call('save')
            ->assertNotified();
    });

    it('can delete removable role', function (): void {
        $roleToDelete = Role::create([
            'name' => 'temporary',
            'display_name' => 'Temporary',
            'can_be_removed' => true,
        ]);

        Livewire::test(RolePermission::class, ['role' => $roleToDelete])
            ->callAction('delete');

        expect(Role::query()->find($roleToDelete->id))->toBeNull();
    });

    it('delete action is visible for removable roles', function (): void {
        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->assertActionVisible('delete');
    });

    it('delete action is hidden for non-removable roles', function (): void {
        $protectedRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'can_be_removed' => false,
        ]);

        Livewire::test(RolePermission::class, ['role' => $protectedRole])
            ->assertActionHidden('delete');
    });

    it('redirects to users page after deletion', function (): void {
        $roleToDelete = Role::create([
            'name' => 'temporary',
            'display_name' => 'Temporary',
            'can_be_removed' => true,
        ]);

        Livewire::test(RolePermission::class, ['role' => $roleToDelete])
            ->callAction('delete')
            ->assertRedirect(route('shopper.settings.users'));
    });

    it('can create new permission and assign to role via action', function (): void {
        $initialCount = Permission::query()->count();

        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->callAction('createPermission', [
                'name' => 'manage_orders',
                'display_name' => 'Manage Orders',
                'description' => 'Can view and manage orders',
            ])
            ->assertHasNoFormErrors()
            ->assertDispatched('permissionAdded')
            ->assertNotified(__('shopper::notifications.users_roles.permission_add'));

        $this->role->refresh();

        expect(Permission::query()->count())->toBe($initialCount + 1)
            ->and(Permission::query()->where('name', 'manage_orders')->exists())->toBeTrue()
            ->and($this->role->hasPermissionTo('manage_orders'))->toBeTrue();
    });

    it('validates required fields when creating permission', function (): void {
        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->callAction('createPermission', [])
            ->assertHasFormErrors(['name' => 'required', 'display_name' => 'required']);
    });

    it('validates unique permission name when creating permission', function (): void {
        Permission::create(['name' => 'manage_products', 'display_name' => 'Manage Products']);

        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->callAction('createPermission', [
                'name' => 'manage_products',
                'display_name' => 'Manage Products',
            ])
            ->assertHasFormErrors(['name' => 'unique']);
    });

    it('validates max length constraints when creating permission', function (): void {
        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->callAction('createPermission', [
                'name' => str_repeat('a', 31),
                'display_name' => str_repeat('b', 76),
            ])
            ->assertHasFormErrors(['name' => 'max', 'display_name' => 'max']);
    });

    it('allows optional group_name and description when creating permission', function (): void {
        $initialCount = Permission::query()->count();

        Livewire::test(RolePermission::class, ['role' => $this->role])
            ->callAction('createPermission', [
                'name' => 'edit_blog',
                'display_name' => 'Edit Blog',
            ])
            ->assertHasNoFormErrors();

        expect(Permission::query()->count())->toBe($initialCount + 1);
    });
})->group('livewire', 'settings', 'team');
