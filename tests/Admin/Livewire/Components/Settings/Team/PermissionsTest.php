<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Settings\Team\Permissions;
use Shopper\Models\Permission;
use Shopper\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('view_users');
    $this->actingAs($this->user);

    $this->role = Role::create([
        'name' => 'test-role',
        'display_name' => 'Test Role',
        'can_be_removed' => true,
    ]);
    $this->permission = Permission::query()->create([
        'name' => 'test.permission',
        'display_name' => 'Test Permission',
        'group_name' => 'Test Group',
    ]);
});

describe(Permissions::class, function (): void {
    it('can render permissions component', function (): void {
        Livewire::test(Permissions::class, ['role' => $this->role])
            ->assertOk();
    });

    it('can toggle permission to role', function (): void {
        expect($this->role->hasPermissionTo('test.permission'))->toBeFalse();

        Livewire::test(Permissions::class, ['role' => $this->role])
            ->call('togglePermission', $this->permission->id);

        $this->role->refresh();

        expect($this->role->hasPermissionTo('test.permission'))->toBeTrue();
    });

    it('can revoke permission from role', function (): void {
        $this->role->givePermissionTo('test.permission');

        expect($this->role->hasPermissionTo('test.permission'))->toBeTrue();

        Livewire::test(Permissions::class, ['role' => $this->role])
            ->call('togglePermission', $this->permission->id);

        $this->role->refresh();

        expect($this->role->hasPermissionTo('test.permission'))->toBeFalse();
    });

    it('sends notification when permission is granted', function (): void {
        Livewire::test(Permissions::class, ['role' => $this->role])
            ->call('togglePermission', $this->permission->id)
            ->assertNotified();
    });

    it('sends notification when permission is revoked', function (): void {
        $this->role->givePermissionTo('test.permission');

        Livewire::test(Permissions::class, ['role' => $this->role])
            ->call('togglePermission', $this->permission->id)
            ->assertNotified();
    });

    it('can remove permission', function (): void {
        $permissionToDelete = Permission::create([
            'name' => 'delete.me',
            'display_name' => 'Delete Me',
            'group_name' => 'Test',
        ]);

        Livewire::test(Permissions::class, ['role' => $this->role])
            ->call('removePermission', $permissionToDelete->id);

        expect(Permission::query()->find($permissionToDelete->id))->toBeNull();
    });

    it('sends notification when permission is removed', function (): void {
        $permissionToDelete = Permission::create([
            'name' => 'delete.me',
            'display_name' => 'Delete Me',
            'group_name' => 'Test',
        ]);

        Livewire::test(Permissions::class, ['role' => $this->role])
            ->call('removePermission', $permissionToDelete->id)
            ->assertNotified();
    });

    it('displays permissions grouped by group name', function (): void {
        Permission::create(['name' => 'product.create', 'display_name' => 'Create Product', 'group_name' => 'Products']);
        Permission::create(['name' => 'product.edit', 'display_name' => 'Edit Product', 'group_name' => 'Products']);
        Permission::create(['name' => 'order.view', 'display_name' => 'View Order', 'group_name' => 'Orders']);

        $component = Livewire::test(Permissions::class, ['role' => $this->role]);

        $groupPermissions = $component->viewData('groupPermissions');

        expect($groupPermissions)->toBeInstanceOf(Illuminate\Support\Collection::class)
            ->and($groupPermissions->has('Products'))->toBeTrue()
            ->and($groupPermissions->has('Orders'))->toBeTrue()
            ->and($groupPermissions->get('Products'))->toHaveCount(2)
            ->and($groupPermissions->get('Orders'))->toHaveCount(1);
    });

    it('listens to permissionAdded event', function (): void {
        $component = Livewire::test(Permissions::class, ['role' => $this->role]);

        Permission::create([
            'name' => 'new.permission',
            'display_name' => 'New Permission',
            'group_name' => 'Test',
        ]);

        $component->dispatch('permissionAdded');

        $component->assertOk();
    });
})->group('livewire', 'components', 'settings');
