<?php

declare(strict_types=1);

use Shopper\Core\Models\Permission;
use Shopper\Core\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

describe(Role::class, function (): void {
    it('has users relationship', function (): void {
        $role = Role::create(['name' => 'test-role']);
        $users = User::factory()->count(3)->create();

        foreach ($users as $user) {
            $user->assignRole($role);
        }

        expect($role->users()->count())->toBeGreaterThanOrEqual(3);
    });

    it('has permissions relationship', function (): void {
        $role = Role::create(['name' => 'test-role-perms']);

        $permissions = collect(['view_products', 'edit_products', 'delete_products'])->map(
            fn ($name) => Permission::query()->firstOrCreate(['name' => $name])
        );

        $role->givePermissionTo($permissions);

        expect($role->permissions()->count())->toBeGreaterThanOrEqual(3);
    });

    it('can be assigned to user', function (): void {
        $role = Role::create(['name' => 'editor']);
        $user = User::factory()->create();

        $user->assignRole($role);

        expect($user->hasRole('editor'))->toBeTrue();
    });

    it('checks if role is admin', function (): void {
        $adminRole = Role::query()->firstOrCreate(['name' => config('shopper.core.roles.admin')]);
        $editorRole = Role::create(['name' => 'editor-role']);

        expect($adminRole->isAdmin())->toBeTrue()
            ->and($editorRole->isAdmin())->toBeFalse();
    });

    it('casts can_be_removed to boolean', function (): void {
        $role = Role::create(['name' => 'removable-role', 'can_be_removed' => 1]);

        expect($role->can_be_removed)->toBeTrue()
            ->and($role->can_be_removed)->toBeBool();
    });
})->group('role', 'models');
