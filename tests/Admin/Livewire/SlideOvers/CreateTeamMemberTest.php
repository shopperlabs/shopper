<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use Shopper\Livewire\SlideOvers\CreateTeamMember;
use Shopper\Models\Role;
use Shopper\Notifications\AdminSendCredentials;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->role = Role::query()->where('name', 'manager')->first();

    $this->user->givePermissionTo(['system.users', 'system.settings', ...$this->role->permissions]);
    $this->actingAs($this->user);
});

describe(CreateTeamMember::class, function (): void {
    it('can create new team member', function (): void {
        $initialCount = User::query()->count();

        Livewire::test(CreateTeamMember::class)
            ->assertSchemaExists('form')
            ->fillForm([
                'email' => 'member@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'gender' => 'male',
                'phone_number' => '+12125550123',
                'roles' => [$this->role->id],
                'send_mail' => false,
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertDispatched('teamUpdate')
            ->assertDispatched('closePanel');

        $newUser = User::query()->where('email', 'member@example.com')->first();

        expect(User::query()->count())->toBe($initialCount + 1)
            ->and($newUser)->not->toBeNull()
            ->and($newUser->first_name)->toBe('John')
            ->and($newUser->last_name)->toBe('Doe')
            ->and($newUser->hasRole('manager'))->toBeTrue();
    });

    it('validates required fields', function (): void {
        Livewire::test(CreateTeamMember::class)
            ->assertSchemaExists('form')
            ->fillForm()
            ->call('store')
            ->assertHasFormErrors([
                'email' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'roles' => 'required',
            ]);
    });

    it('validates email format', function (): void {
        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'email' => 'invalid-email',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'roles' => [$this->role->id],
            ])
            ->call('store')
            ->assertHasFormErrors(['email' => 'email']);
    });

    it('validates email uniqueness', function (): void {
        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'email' => $this->user->email,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'roles' => [$this->role->id],
            ])
            ->call('store')
            ->assertHasFormErrors(['email' => 'unique']);
    });

    it('invites the new member with a password reset link', function (): void {
        Notification::fake();

        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'email' => 'notify@example.com',
                'first_name' => 'Bob',
                'last_name' => 'Wilson',
                'roles' => [$this->role->id],
                'send_mail' => true,
            ])
            ->call('store');

        $newUser = User::query()->where('email', 'notify@example.com')->first();

        Notification::assertSentTo(
            $newUser,
            AdminSendCredentials::class,
            fn (AdminSendCredentials $notification): bool => Password::tokenExists($newUser, $notification->token)
                && $notification->toMail($newUser)->actionUrl === route('shopper.password.reset', [
                    'token' => $notification->token,
                    'email' => $newUser->email,
                ]),
        );
    });

    it('warns when the invitation link was refused', function (): void {
        Notification::fake();
        DB::table(config('auth.passwords.users.table'))->insert([
            'email' => 'recreated@example.com',
            'token' => Hash::make('previous-token'),
            'created_at' => now(),
        ]);

        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'email' => 'recreated@example.com',
                'first_name' => 'Bob',
                'last_name' => 'Wilson',
                'roles' => [$this->role->id],
                'send_mail' => true,
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertNotified(__('shopper::pages/settings/staff.invitation_not_sent'));

        Notification::assertNothingSent();
    });

    it('does not send email when send_mail is false', function (): void {
        Notification::fake();

        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'email' => 'nomail@example.com',
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'roles' => [$this->role->id],
                'send_mail' => false,
            ])
            ->call('store');

        Notification::assertNothingSent();
    });

    it('assigns role to new user', function (): void {
        /** @var Role $secondRole */
        $secondRole = Role::query()->skip(1)->first();

        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'email' => 'role@example.com',
                'first_name' => 'Role',
                'last_name' => 'User',
                'roles' => [$secondRole->id],
                'send_mail' => false,
            ])
            ->call('store');

        $newUser = User::query()->where('email', 'role@example.com')->first();

        expect($newUser->hasRole($secondRole->name))->toBeTrue();
    });

    it('blocks `store` for users without `system.users`', function (): void {
        $reader = User::factory()->create();
        $reader->givePermissionTo('system.settings');
        $this->actingAs($reader);

        $initialCount = User::query()->count();

        Livewire::test(CreateTeamMember::class)
            ->call('store');

        expect(User::query()->count())->toBe($initialCount)
            ->and(User::query()->where('email', 'denied@example.com')->exists())->toBeFalse();
    });
})->group('livewire', 'team');
