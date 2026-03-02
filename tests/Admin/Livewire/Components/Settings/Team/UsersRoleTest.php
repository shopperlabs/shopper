<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Settings\Team\UsersRole;
use Shopper\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->adminUser = User::factory()->create();
    $this->adminUser->assignRole(config('shopper.admin.roles.admin'));
    $this->actingAs($this->adminUser);

    $this->role = Role::create([
        'name' => 'editor',
        'display_name' => 'Editor',
        'can_be_removed' => true,
    ]);
});

describe(UsersRole::class, function (): void {
    it('can render users role component', function (): void {
        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->assertOk();
    });

    it('displays users with specific role', function (): void {
        $editorUser = User::factory()->create();
        $editorUser->assignRole($this->role->name);

        $otherUser = User::factory()->create();
        $otherUser->assignRole(config('shopper.admin.roles.manager'));

        $component = Livewire::test(UsersRole::class, ['role' => $this->role]);

        $component->loadTable()
            ->assertCanSeeTableRecords([$editorUser])
            ->assertCanNotSeeTableRecords([$otherUser]);
    });

    it('displays user full name column', function (): void {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        $user->assignRole($this->role->name);

        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->loadTable()
            ->assertCanSeeTableRecords([$user]);
    });

    it('displays email column with verification status', function (): void {
        $verifiedUser = User::factory()->create([
            'email' => 'verified@example.com',
            'email_verified_at' => now(),
        ]);
        $verifiedUser->assignRole($this->role->name);

        $unverifiedUser = User::factory()->create([
            'email' => 'unverified@example.com',
            'email_verified_at' => null,
        ]);
        $unverifiedUser->assignRole($this->role->name);

        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->loadTable()
            ->assertCanSeeTableRecords([$verifiedUser, $unverifiedUser]);
    });

    it('displays roles label column', function (): void {
        $user = User::factory()->create();
        $user->assignRole($this->role->name);

        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->loadTable()
            ->assertCanSeeTableRecords([$user]);
    });

    it('displays access level for admin users', function (): void {
        $adminUser = User::factory()->create();
        $adminUser->assignRole(config('shopper.admin.roles.admin'));

        Livewire::test(UsersRole::class, ['role' => Role::query()->where('name', config('shopper.admin.roles.admin'))->first()])
            ->loadTable()
            ->assertCanSeeTableRecords([$adminUser]);
    });

    it('displays access level for limited users', function (): void {
        $limitedUser = User::factory()->create();
        $limitedUser->assignRole($this->role->name);

        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->loadTable()
            ->assertCanSeeTableRecords([$limitedUser]);
    });

    it('admin can see delete action for non-admin users', function (): void {
        $regularUser = User::factory()->create();
        $regularUser->assignRole($this->role->name);

        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->assertTableActionVisible('delete', $regularUser);
    });

    it('admin cannot see delete action for admin users', function (): void {
        $anotherAdmin = User::factory()->create();
        $anotherAdmin->assignRole(config('shopper.admin.roles.admin'));

        Livewire::test(UsersRole::class, ['role' => Role::query()->where('name', config('shopper.admin.roles.admin'))->first()])
            ->assertTableActionHidden('delete', $anotherAdmin);
    });

    it('non-admin cannot see delete action', function (): void {
        $nonAdminUser = User::factory()->create();
        $nonAdminUser->assignRole($this->role->name);
        $this->actingAs($nonAdminUser);

        $targetUser = User::factory()->create();
        $targetUser->assignRole($this->role->name);

        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->assertTableActionHidden('delete', $targetUser);
    });

    it('admin can delete non-admin user', function (): void {
        $userToDelete = User::factory()->create();
        $userToDelete->assignRole($this->role->name);

        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->callTableAction('delete', $userToDelete);

        expect(User::query()->find($userToDelete->id))->toBeNull();
    });

    it('sends notification after user deletion', function (): void {
        $userToDelete = User::factory()->create();
        $userToDelete->assignRole($this->role->name);

        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->callTableAction('delete', $userToDelete)
            ->assertNotified();
    });

    it('displays empty state when no users have the role', function (): void {
        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->assertCountTableRecords(0);
    });

    it('filters users correctly by role', function (): void {
        $supervisorRole = Role::create(['name' => 'supervisor', 'display_name' => 'Supervisor']);

        $editor1 = User::factory()->create();
        $editor1->assignRole($this->role->name);

        $editor2 = User::factory()->create();
        $editor2->assignRole($this->role->name);

        $supervisor = User::factory()->create();
        $supervisor->assignRole($supervisorRole->name);

        Livewire::test(UsersRole::class, ['role' => $this->role])
            ->loadTable()
            ->assertCanSeeTableRecords([$editor1, $editor2])
            ->assertCanNotSeeTableRecords([$supervisor]);
    });
})->group('livewire', 'components', 'settings', 'team');
