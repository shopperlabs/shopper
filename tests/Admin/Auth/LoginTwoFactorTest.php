<?php

declare(strict_types=1);

use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;
use Shopper\Livewire\Pages\Auth\Login;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.auth.2fa_enabled', true);

    $google2fa = app(Google2FA::class);
    $this->secret = $google2fa->generateSecretKey();

    $this->user = User::factory()->create([
        'two_factor_secret' => encrypt($this->secret),
        'two_factor_recovery_codes' => encrypt(json_encode([
            'recovery-code-1',
            'recovery-code-2',
        ])),
    ]);
});

describe('Login Two-Factor Authentication', function (): void {
    it('shows two factor challenge when user has 2fa enabled', function (): void {
        Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('authenticate')
            ->assertSet('challengedUserId', fn ($value): bool => $value !== null)
            ->assertNotDispatched('redirect')
            ->assertNoRedirect();

        $this->assertGuest(config('shopper.auth.guard'));
    });

    it('authenticates user with valid two factor code', function (): void {
        $google2fa = app(Google2FA::class);
        $validCode = $google2fa->getCurrentOtp($this->secret);

        $component = Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('authenticate');

        $component
            ->set('code', $validCode)
            ->call('authenticate')
            ->assertRedirect(route('shopper.dashboard'));

        $this->assertAuthenticatedAs($this->user, config('shopper.auth.guard'));
    });

    it('rejects authentication with invalid two factor code', function (): void {
        $component = Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('authenticate');

        $component
            ->set('code', 'invalid-code')
            ->call('authenticate')
            ->assertHasErrors(['code']);

        $this->assertGuest(config('shopper.auth.guard'));
    });

    it('authenticates user with valid recovery code', function (): void {
        $component = Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('authenticate');

        $component
            ->set('useRecoveryCode', true)
            ->set('recoveryCode', 'recovery-code-1')
            ->call('authenticate')
            ->assertRedirect(route('shopper.dashboard'));

        $this->assertAuthenticatedAs($this->user, config('shopper.auth.guard'));
    });

    it('rejects authentication with invalid recovery code', function (): void {
        $component = Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('authenticate');

        $component
            ->set('useRecoveryCode', true)
            ->set('recoveryCode', 'invalid-recovery-code')
            ->call('authenticate')
            ->assertHasErrors(['recoveryCode']);

        $this->assertGuest(config('shopper.auth.guard'));
    });

    it('consumes recovery code after successful use', function (): void {
        $component = Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('authenticate');

        $component
            ->set('useRecoveryCode', true)
            ->set('recoveryCode', 'recovery-code-1')
            ->call('authenticate');

        $recoveryCodes = json_decode(decrypt($this->user->fresh()->two_factor_recovery_codes), true);

        expect($recoveryCodes)->not->toContain('recovery-code-1');
    });

    it('resets challenge state when calling resetChallenge', function (): void {
        $component = Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('authenticate');

        $component
            ->assertSet('challengedUserId', fn ($value): bool => $value !== null)
            ->call('resetChallenge')
            ->assertSet('challengedUserId', null)
            ->assertSet('code', '')
            ->assertSet('recoveryCode', '')
            ->assertSet('useRecoveryCode', false);
    });

    it('does not challenge when 2fa is disabled in config', function (): void {
        config()->set('shopper.auth.2fa_enabled', false);

        Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('authenticate')
            ->assertSet('challengedUserId', null)
            ->assertRedirect(route('shopper.dashboard'));

        $this->assertAuthenticatedAs($this->user, config('shopper.auth.guard'));
    });

    it('does not challenge user without two factor secret', function (): void {
        $userWithout2fa = User::factory()->create([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ]);

        Livewire::test(Login::class)
            ->set('email', $userWithout2fa->email)
            ->set('password', 'password')
            ->call('authenticate')
            ->assertSet('challengedUserId', null)
            ->assertRedirect(route('shopper.dashboard'));

        $this->assertAuthenticatedAs($userWithout2fa, config('shopper.auth.guard'));
    });

    it('re-validates credentials on two factor submission', function (): void {
        $component = Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('authenticate');

        $component
            ->set('password', 'wrong-password')
            ->set('code', '123456')
            ->call('authenticate')
            ->assertHasErrors(['email']);

        $this->assertGuest(config('shopper.auth.guard'));
    });

    it('remembers user when remember flag is set with two factor', function (): void {
        $google2fa = app(Google2FA::class);
        $validCode = $google2fa->getCurrentOtp($this->secret);

        $component = Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->set('remember', true)
            ->call('authenticate');

        $component
            ->set('code', $validCode)
            ->call('authenticate')
            ->assertRedirect(route('shopper.dashboard'));

        $this->assertAuthenticatedAs($this->user, config('shopper.auth.guard'));
    });
})->group('two-factor', 'authenticate');
