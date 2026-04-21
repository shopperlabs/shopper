<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Settings\Team\AdministratorsList;
use Shopper\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->adminUser = User::factory()->create();
    $this->adminUser->assignRole(config('shopper.admin.roles.admin'));
    $this->actingAs($this->adminUser);
});

describe(AdministratorsList::class, function (): void {
    it('lists every administrator except customers when no role is given', function (): void {
        $admin = User::factory()->create();
        $admin->assignRole(config('shopper.admin.roles.admin'));

        $manager = User::factory()->create();
        $manager->assignRole(config('shopper.admin.roles.manager'));

        $bloggerRole = Role::create(['name' => 'blogger', 'display_name' => 'Blogger']);
        $blogger = User::factory()->create();
        $blogger->assignRole($bloggerRole->name);

        $customer = User::factory()->create();
        $customer->assignRole(config('shopper.admin.roles.user'));

        Livewire::test(AdministratorsList::class)
            ->loadTable()
            ->assertCanSeeTableRecords([$this->adminUser, $admin, $manager, $blogger])
            ->assertCanNotSeeTableRecords([$customer]);
    });

    it('lists only users of the given role when role is provided', function (): void {
        $editorRole = Role::create(['name' => 'editor', 'display_name' => 'Editor']);

        $editor = User::factory()->create();
        $editor->assignRole($editorRole->name);

        $manager = User::factory()->create();
        $manager->assignRole(config('shopper.admin.roles.manager'));

        Livewire::test(AdministratorsList::class, ['role' => $editorRole])
            ->loadTable()
            ->assertCanSeeTableRecords([$editor])
            ->assertCanNotSeeTableRecords([$manager, $this->adminUser]);
    });

    it('allows admin to delete a non-admin user', function (): void {
        $manager = User::factory()->create();
        $manager->assignRole(config('shopper.admin.roles.manager'));

        Livewire::test(AdministratorsList::class)
            ->callTableAction('delete', $manager)
            ->assertNotified();

        expect(User::query()->find($manager->id))->toBeNull();
    });

    it('allows admin to delete another admin when not the last one', function (): void {
        $anotherAdmin = User::factory()->create();
        $anotherAdmin->assignRole(config('shopper.admin.roles.admin'));

        Livewire::test(AdministratorsList::class)
            ->callTableAction('delete', $anotherAdmin)
            ->assertNotified();

        expect(User::query()->find($anotherAdmin->id))->toBeNull();
    });

    it('prevents admin from deleting the last remaining admin', function (): void {
        Livewire::test(AdministratorsList::class)
            ->assertTableActionHidden('delete', $this->adminUser);
    });

    it('prevents admin from deleting themselves via delete action', function (): void {
        User::factory()->create()->assignRole(config('shopper.admin.roles.admin'));

        Livewire::test(AdministratorsList::class)
            ->assertTableActionHidden('delete', $this->adminUser);
    });

    it('sends notification when copyUserId action is triggered', function (): void {
        $manager = User::factory()->create();
        $manager->assignRole(config('shopper.admin.roles.manager'));

        Livewire::test(AdministratorsList::class)
            ->callTableAction('copyUserId', $manager)
            ->assertNotified(__('shopper::notifications.users_roles.user_id_copied'));
    });

    it('prevents admin from selecting itself in bulk', function (): void {
        $component = Livewire::test(AdministratorsList::class)->instance();

        expect($component->canBulkSelect($this->adminUser))->toBeFalse();
    });

    it('allows admin to select another admin in bulk when not the last one', function (): void {
        $anotherAdmin = User::factory()->create();
        $anotherAdmin->assignRole(config('shopper.admin.roles.admin'));

        $component = Livewire::test(AdministratorsList::class)->instance();

        expect($component->canBulkSelect($anotherAdmin))->toBeTrue();
    });

    it('prevents admin from selecting the last remaining admin in bulk', function (): void {
        $manager = User::factory()->create();
        $manager->assignRole(config('shopper.admin.roles.manager'));
        $this->actingAs($manager);

        $component = Livewire::test(AdministratorsList::class)->instance();

        expect($component->canBulkSelect($this->adminUser))->toBeFalse();
    });

    it('allows admin to select a manager in bulk', function (): void {
        $manager = User::factory()->create();
        $manager->assignRole(config('shopper.admin.roles.manager'));

        $component = Livewire::test(AdministratorsList::class)->instance();

        expect($component->canBulkSelect($manager))->toBeTrue();
    });

    it('prevents manager from selecting an admin or another manager in bulk', function (): void {
        $manager = User::factory()->create();
        $manager->assignRole(config('shopper.admin.roles.manager'));
        $this->actingAs($manager);

        $anotherManager = User::factory()->create();
        $anotherManager->assignRole(config('shopper.admin.roles.manager'));

        $component = Livewire::test(AdministratorsList::class)->instance();

        expect($component->canBulkSelect($this->adminUser))->toBeFalse()
            ->and($component->canBulkSelect($anotherManager))->toBeFalse();
    });
})->group('livewire', 'components', 'settings', 'team');
