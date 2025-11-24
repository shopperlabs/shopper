<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Shopper\Core\Models\Role;
use Shopper\Livewire\SlideOvers\CreateTeamMember;
use Shopper\Notifications\AdminSendCredentials;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->role = Role::query()->where('name', 'manager')->first();
});

describe(CreateTeamMember::class, function (): void {
    it('can render create team member form', function (): void {
        Livewire::test(CreateTeamMember::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.slide-overs.create-team-member');
    });

    it('can create new team member', function (): void {
        $initialCount = User::query()->count();

        Livewire::test(CreateTeamMember::class)
            ->assertFormExists()
            ->fillForm([
                'email' => 'member@example.com',
                'password' => 'password123',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'gender' => 'male',
                'phone_number' => '+1234567890',
                'role_id' => $this->role->id,
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
            ->assertFormExists()
            ->fillForm()
            ->call('store')
            ->assertHasFormErrors([
                'email' => 'required',
                'password' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'role_id' => 'required',
            ]);
    });

    it('validates email format', function (): void {
        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'email' => 'invalid-email',
                'password' => 'password123',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'role_id' => $this->role->id,
            ])
            ->call('store')
            ->assertHasFormErrors(['email' => 'email']);
    });

    it('hashes password before storing', function (): void {
        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'email' => 'test@example.com',
                'password' => 'plainpassword',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'role_id' => $this->role->id,
                'send_mail' => false,
            ])
            ->call('store');

        $newUser = User::query()->where('email', 'test@example.com')->first();

        expect(Hash::check('plainpassword', $newUser->password))->toBeTrue();
    });

    it('sends email when send_mail is true', function (): void {
        Notification::fake();

        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'email' => 'notify@example.com',
                'password' => 'password123',
                'first_name' => 'Bob',
                'last_name' => 'Wilson',
                'role_id' => $this->role->id,
                'send_mail' => true,
            ])
            ->call('store');

        $newUser = User::query()->where('email', 'notify@example.com')->first();

        Notification::assertSentTo($newUser, AdminSendCredentials::class);
    });

    it('does not send email when send_mail is false', function (): void {
        Notification::fake();

        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'email' => 'nomail@example.com',
                'password' => 'password123',
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'role_id' => $this->role->id,
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
                'password' => 'password123',
                'first_name' => 'Role',
                'last_name' => 'User',
                'role_id' => $secondRole->id,
                'send_mail' => false,
            ])
            ->call('store');

        $newUser = User::query()->where('email', 'role@example.com')->first();

        expect($newUser->hasRole($secondRole->name))->toBeTrue();
    });
})->group('livewire', 'team');
