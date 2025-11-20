<?php

declare(strict_types=1);

use PragmaRX\Google2FA\Google2FA;
use Shopper\Core\Models\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $google2fa = app(Google2FA::class);
    $secret = $google2fa->generateSecretKey();

    $this->user = User::factory()->create([
        'two_factor_secret' => encrypt($secret),
        'two_factor_recovery_codes' => encrypt(json_encode([
            'recovery-code-1',
            'recovery-code-2',
        ])),
    ]);
});

describe('TwoFactorAuthenticatedController', function (): void {
    it('redirects to login if no challenged user in session', function (): void {
        $this->get(route('shopper.two-factor.login'))
            ->assertRedirect(route('shopper.login'));
    });

    it('shows two factor challenge page when session has challenged user', function (): void {
        $this->withSession([
            'login.id' => $this->user->id,
            'login.remember' => false,
        ]);

        $this->get(route('shopper.two-factor.login'))
            ->assertOk()
            ->assertViewIs('shopper::auth.two-factor-login');
    });

    it('authenticates user with valid two factor code', function (): void {
        $google2fa = app(Google2FA::class);
        $validCode = $google2fa->getCurrentOtp(decrypt($this->user->two_factor_secret));

        $this->withSession([
            'login.id' => $this->user->id,
            'login.remember' => false,
        ]);

        $this->post(route('shopper.two-factor.login'), [
            'code' => $validCode,
        ])
            ->assertRedirect(route('shopper.dashboard'));

        $this->assertAuthenticatedAs($this->user, config('shopper.auth.guard'));
    });

    it('rejects authentication with invalid two factor code', function (): void {
        $this->withSession([
            'login.id' => $this->user->id,
            'login.remember' => false,
        ]);

        $this->post(route('shopper.two-factor.login'), [
            'code' => 'invalid-code',
        ])
            ->assertRedirect();

        $this->assertGuest(config('shopper.auth.guard'));
    });

    it('authenticates user with valid recovery code', function (): void {
        $this->withSession([
            'login.id' => $this->user->id,
            'login.remember' => false,
        ]);

        $this->post(route('shopper.two-factor.login'), [
            'recovery_code' => 'recovery-code-1',
        ])
            ->assertRedirect(route('shopper.dashboard'));

        $this->assertAuthenticatedAs($this->user, config('shopper.auth.guard'));

        $recoveryCodes = json_decode(decrypt($this->user->fresh()->two_factor_recovery_codes), true);

        expect($recoveryCodes)->not->toContain('recovery-code-1');
    });

    it('rejects authentication with invalid recovery code', function (): void {
        $this->withSession([
            'login.id' => $this->user->id,
            'login.remember' => false,
        ]);

        $this->post(route('shopper.two-factor.login'), [
            'recovery_code' => 'invalid-recovery-code',
        ])
            ->assertRedirect();

        $this->assertGuest(config('shopper.auth.guard'));
    });

    it('removes login session data after successful authentication', function (): void {
        $google2fa = app(Google2FA::class);
        $validCode = $google2fa->getCurrentOtp(decrypt($this->user->two_factor_secret));

        $this->withSession([
            'login.id' => $this->user->id,
            'login.remember' => false,
        ]);

        $this->post(route('shopper.two-factor.login'), [
            'code' => $validCode,
        ]);

        expect(session()->has('login.id'))->toBeFalse()
            ->and(session()->has('login.remember'))->toBeFalse();
    });

    it('remembers user when remember flag is true', function (): void {
        $google2fa = app(Google2FA::class);
        $validCode = $google2fa->getCurrentOtp(decrypt($this->user->two_factor_secret));

        $this->withSession([
            'login.id' => $this->user->id,
            'login.remember' => true,
        ]);

        $this->post(route('shopper.two-factor.login'), [
            'code' => $validCode,
        ])
            ->assertRedirect(route('shopper.dashboard'));

        $this->assertAuthenticatedAs($this->user, config('shopper.auth.guard'));
    });
})->group('two-factor', 'controllers');
