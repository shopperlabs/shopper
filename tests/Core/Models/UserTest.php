<?php

declare(strict_types=1);

use Shopper\Core\Models\Address;
use Shopper\Core\Models\Role;
use Shopper\Core\Models\User;

uses(Tests\TestCase::class);

describe(User::class, function (): void {
    it('deletes user addresses and roles when user is deleted', function (): void {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'test-role']);
        $user->roles()->attach($role);

        Address::factory()->count(2)->create(['user_id' => $user->id]);

        $addressIds = $user->addresses->pluck('id')->toArray();

        $user->delete();

        expect(Address::whereIn('id', $addressIds)->exists())->toBeFalse()
            ->and($user->roles()->count())->toBe(0);
    });

    it('checks if user is admin', function (): void {
        $user = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => config('shopper.core.roles.admin')]);

        $user->assignRole($adminRole);

        expect($user->isAdmin())->toBeTrue();
    });

    it('checks if user is not admin', function (): void {
        $user = User::factory()->create();

        expect($user->isAdmin())->toBeFalse();
    });

    it('checks if user is manager', function (): void {
        $user = User::factory()->create();
        $managerRole = Role::firstOrCreate(['name' => config('shopper.core.roles.manager')]);

        $user->assignRole($managerRole);

        expect($user->isManager())->toBeTrue();
    });

    it('checks if user is not manager', function (): void {
        $user = User::factory()->create();

        expect($user->isManager())->toBeFalse();
    });

    it('checks if user email is verified', function (): void {
        $verifiedUser = User::factory()->create(['email_verified_at' => now()]);
        $unverifiedUser = User::factory()->create(['email_verified_at' => null]);

        expect($verifiedUser->isVerified())->toBeTrue()
            ->and($unverifiedUser->isVerified())->toBeFalse();
    });

    it('scopes customers correctly', function (): void {
        $customerRole = Role::firstOrCreate(['name' => config('shopper.core.roles.user')]);
        $customers = User::factory()->count(3)->create();
        $customers->each(fn ($user) => $user->assignRole($customerRole));

        User::factory()->count(2)->create();

        expect(User::customers()->count())->toBe(3);
    });

    it('scopes administrators correctly', function (): void {
        $adminRole = Role::firstOrCreate(['name' => config('shopper.core.roles.admin')]);
        $managerRole = Role::firstOrCreate(['name' => config('shopper.core.roles.manager')]);

        $admins = User::factory()->count(2)->create();
        $admins->each(fn ($user) => $user->assignRole($adminRole));

        $manager = User::factory()->create();
        $manager->assignRole($managerRole);

        User::factory()->count(3)->create();

        expect(User::administrators()->count())->toBe(3);
    });

    it('has full name accessor', function (): void {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        expect($user->full_name)->toBe('John Doe');
    });

    it('has orders relationship', function (): void {
        $user = User::factory()->create();

        expect($user->orders())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    });

    it('has addresses relationship', function (): void {
        $user = User::factory()->create();
        Address::factory()->count(3)->create(['user_id' => $user->id]);

        expect($user->addresses()->count())->toBe(3);
    });
})->group('user', 'models');
