<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\SlideOvers\CreateTeamMember;
use Shopper\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

it('lets a manager run the catalog, sales and customers without settings, team or locations', function (): void {
    $manager = User::factory()->create();
    $manager->assignRole(config('shopper.admin.roles.manager'));

    expect($manager->can('system.dashboard'))->toBeTrue()
        ->and($manager->can('products.edit'))->toBeTrue()
        ->and($manager->can('categories.create'))->toBeTrue()
        ->and($manager->can('orders.edit'))->toBeTrue()
        ->and($manager->can('discounts.create'))->toBeTrue()
        ->and($manager->can('customers.edit'))->toBeTrue()
        ->and($manager->can('customers.delete'))->toBeFalse()
        ->and($manager->can('system.settings'))->toBeFalse()
        ->and($manager->can('system.users'))->toBeFalse()
        ->and($manager->can('inventories.browse'))->toBeFalse();
});

it('denies the manager role to staff who do not hold its permissions', function (): void {
    $support = Role::create([
        'name' => 'support',
        'display_name' => 'Support',
        'can_be_removed' => true,
    ]);
    $support->givePermissionTo(['system.dashboard', 'system.users']);

    $staff = User::factory()->create();
    $staff->assignRole($support);
    $this->actingAs($staff);

    $managerRole = Role::query()
        ->where('name', config('shopper.admin.roles.manager'))
        ->firstOrFail();

    Livewire::test(CreateTeamMember::class)
        ->fillForm([
            'email' => 'manager@example.com',
            'password' => 'password12345678',
            'first_name' => 'New',
            'last_name' => 'Manager',
            'roles' => [$managerRole->id],
            'send_mail' => false,
        ])
        ->call('store')
        ->assertHasFormErrors(['roles.0' => 'in']);

    expect(User::query()->where('email', 'manager@example.com')->exists())->toBeFalse();
});

it('denies a role without permissions to non administrators, like the empty manager role of an older install', function (): void {
    $managerRole = Role::query()
        ->where('name', config('shopper.admin.roles.manager'))
        ->firstOrFail();
    $managerRole->syncPermissions([]);

    $staff = User::factory()->create();
    $staff->givePermissionTo(['system.dashboard', 'system.users']);
    $this->actingAs($staff);

    Livewire::test(CreateTeamMember::class)
        ->fillForm([
            'email' => 'manager@example.com',
            'password' => 'password12345678',
            'first_name' => 'New',
            'last_name' => 'Manager',
            'roles' => [$managerRole->id],
            'send_mail' => false,
        ])
        ->call('store')
        ->assertHasFormErrors(['roles.0' => 'in']);

    expect(User::query()->where('email', 'manager@example.com')->exists())->toBeFalse();
});
