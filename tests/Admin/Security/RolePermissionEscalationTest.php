<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Settings\Team\Permissions as RolePermissions;
use Shopper\Livewire\Pages\Settings\Team\RolePermission;
use Shopper\Livewire\SlideOvers\CreateTeamMember;
use Shopper\Models\Permission;
use Shopper\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->administratorRole = Role::query()
        ->where('name', config('shopper.admin.roles.admin'))
        ->firstOrFail();

    $this->managerRole = Role::query()
        ->where('name', config('shopper.admin.roles.manager'))
        ->firstOrFail();

    $this->managerRole->givePermissionTo(['view_users', 'access_setting']);

    $this->staff = User::factory()->create();
    $this->staff->assignRole($this->managerRole);
});

it('denies granting a permission the acting user does not hold', function (): void {
    $this->actingAs($this->staff);

    Livewire::test(RolePermissions::class, ['role' => $this->managerRole])
        ->call('togglePermission', Permission::query()->where('name', 'delete_orders')->firstOrFail()->id);

    expect($this->managerRole->fresh()->hasPermissionTo('delete_orders'))->toBeFalse();
});

it('allows granting a permission the acting user already holds', function (): void {
    $this->managerRole->givePermissionTo('edit_orders');

    $target = Role::create([
        'name' => 'support',
        'display_name' => 'Support',
        'can_be_removed' => true,
    ]);

    $this->actingAs($this->staff);

    Livewire::test(RolePermissions::class, ['role' => $target])
        ->call('togglePermission', Permission::query()->where('name', 'edit_orders')->firstOrFail()->id);

    expect($target->fresh()->hasPermissionTo('edit_orders'))->toBeTrue();
});

it('denies a non administrator any write on the administrator role', function (): void {
    $this->actingAs($this->staff);

    Livewire::test(RolePermission::class, ['role' => $this->administratorRole])
        ->set('data.display_name', 'Hijacked')
        ->call('save');

    expect($this->administratorRole->fresh()->display_name)->not->toBe('Hijacked');
});

it('denies permission creation to a non administrator', function (): void {
    $this->actingAs($this->staff);

    Livewire::test(RolePermission::class, ['role' => $this->managerRole])
        ->assertActionHidden('createPermission')
        ->assertActionHidden('generatePermissions');

    expect(Permission::query()->where('name', 'own_everything')->exists())->toBeFalse();
});

it('refuses to delete a permission the panel depends on', function (): void {
    $this->asAdmin();

    $permission = Permission::query()->where('name', 'access_setting')->firstOrFail();

    Livewire::test(RolePermissions::class, ['role' => $this->managerRole])
        ->call('removePermission', $permission->id);

    expect(Permission::query()->whereKey($permission->id)->exists())->toBeTrue();
});

it('denies a non administrator assigning the administrator role to a new member', function (): void {
    $this->actingAs($this->staff);

    Livewire::test(CreateTeamMember::class)
        ->fillForm([
            'email' => 'intruder@example.com',
            'password' => 'password123',
            'first_name' => 'Intruder',
            'last_name' => 'Doe',
            'role_id' => $this->administratorRole->id,
            'send_mail' => false,
        ])
        ->call('store');

    expect(User::query()->where('email', 'intruder@example.com')->exists())->toBeFalse();
});
