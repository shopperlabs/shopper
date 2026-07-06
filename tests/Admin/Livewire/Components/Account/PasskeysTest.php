<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Laravel\Passkeys\Events\PasskeyDeleted;
use Livewire\Livewire;
use Shopper\Livewire\Components\Account\Passkeys;
use Tests\Core\Stubs\User;

uses(Tests\Admin\PasskeysTestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user, config('shopper.auth.guard'));
});

function createPasskeyFor(User $user, string $name = 'MacBook Pro'): mixed
{
    return $user->passkeys()->create([
        'name' => $name,
        'credential_id' => uniqid('credential-', true),
        'credential' => ['type' => 'public-key'],
    ]);
}

describe(Passkeys::class, function (): void {
    it('can render the passkeys component', function (): void {
        Livewire::test(Passkeys::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.components.account.passkeys');
    });

    it('lists the passkeys of the current user only', function (): void {
        createPasskeyFor($this->user, 'MacBook Pro');
        createPasskeyFor(User::factory()->create(), 'Other device');

        Livewire::test(Passkeys::class)
            ->assertSee('MacBook Pro')
            ->assertDontSee('Other device');
    });

    it('opens the add passkey modal when the password is confirmed', function (): void {
        session(['auth.password_confirmed_at' => time()]);

        Livewire::test(Passkeys::class)
            ->call('openAddPasskeyModal')
            ->assertActionMounted('addPasskey');
    });

    it('requires a confirmed password to open the add passkey modal', function (): void {
        Livewire::test(Passkeys::class)
            ->call('openAddPasskeyModal')
            ->assertStatus(403);
    });

    it('requires a confirmed password when the add passkey action is called directly', function (): void {
        Livewire::test(Passkeys::class)
            ->callAction('addPasskey', data: ['name' => 'Attacker device'])
            ->assertStatus(403)
            ->assertNotDispatched('shopper-passkey-register');
    });

    it('deletes one of the user passkeys after confirming the password', function (): void {
        Event::fake([PasskeyDeleted::class]);

        $passkey = createPasskeyFor($this->user);

        Livewire::test(Passkeys::class)
            ->callAction(
                'deletePasskey',
                data: ['password' => 'password'],
                arguments: ['passkey' => $passkey->getKey()],
            )
            ->assertHasNoActionErrors();

        expect($this->user->passkeys()->count())->toBe(0);

        Event::assertDispatched(PasskeyDeleted::class);
    });

    it('does not delete a passkey with a wrong password', function (): void {
        $passkey = createPasskeyFor($this->user);

        Livewire::test(Passkeys::class)
            ->callAction(
                'deletePasskey',
                data: ['password' => 'wrong-password'],
                arguments: ['passkey' => $passkey->getKey()],
            )
            ->assertHasActionErrors();

        expect($this->user->passkeys()->count())->toBe(1);
    });

    it('cannot delete a passkey belonging to another user', function (): void {
        $foreign = createPasskeyFor(User::factory()->create(), 'Other device');

        Livewire::test(Passkeys::class)
            ->callAction(
                'deletePasskey',
                data: ['password' => 'password'],
                arguments: ['passkey' => $foreign->getKey()],
            );
    })->throws(ModelNotFoundException::class);
});
