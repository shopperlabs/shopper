<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Shopper\Actions\Auth\DisableTwoFactorAuthentication;
use Shopper\Actions\Auth\GenerateNewRecoveryCodes;
use Shopper\Events\TwoFactor\TwoFactorAuthenticationEnabled;
use Shopper\Livewire\Components\Account\TwoFactor;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.auth.2fa_enabled', false);

    $this->user = User::factory()->create();
    $this->actingAs($this->user, config('shopper.auth.guard'));
});

describe(TwoFactor::class, function (): void {
    it('can render two factor component', function (): void {
        Livewire::test(TwoFactor::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.components.account.two-factor');
    });

    it('shows that two factor is not enabled by default', function (): void {
        Livewire::test(TwoFactor::class)
            ->assertSet('enabled', false);
    });

    it('can enable two factor authentication', function (): void {
        Event::fake([TwoFactorAuthenticationEnabled::class]);

        Livewire::test(TwoFactor::class)
            ->call('enableTwoFactorAuthentication', app(Shopper\Actions\Auth\EnableTwoFactorAuthentication::class))
            ->assertSet('showingQrCode', true)
            ->assertSet('showingRecoveryCodes', true);

        $user = $this->user->fresh();

        expect($user->two_factor_secret)->not->toBeNull()
            ->and($user->two_factor_recovery_codes)->not->toBeNull();

        Event::assertDispatched(TwoFactorAuthenticationEnabled::class);
    });

    it('shows enabled state when user has two factor secret', function (): void {
        $this->user->forceFill([
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        ])->save();

        Livewire::test(TwoFactor::class)
            ->assertSet('enabled', true);
    });

    it('can show recovery codes', function (): void {
        $this->user->forceFill([
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        ])->save();

        Livewire::test(TwoFactor::class)
            ->call('showRecoveryCodes')
            ->assertSet('showingRecoveryCodes', true);
    });

    it('can regenerate recovery codes', function (): void {
        $this->user->forceFill([
            'two_factor_secret' => encrypt('original-secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['old-code'])),
        ])->save();

        $oldCodes = $this->user->two_factor_recovery_codes;

        Livewire::test(TwoFactor::class)
            ->call('regenerateRecoveryCodes', app(GenerateNewRecoveryCodes::class))
            ->assertSet('showingRecoveryCodes', true);

        $user = $this->user->fresh();

        expect($user->two_factor_recovery_codes)->not->toBe($oldCodes);
    });

    it('can disable two factor authentication', function (): void {
        $this->user->forceFill([
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        ])->save();

        Livewire::test(TwoFactor::class)
            ->call('disableTwoFactorAuthentication', app(DisableTwoFactorAuthentication::class));

        $user = $this->user->fresh();

        expect($user->two_factor_secret)->toBeNull()
            ->and($user->two_factor_recovery_codes)->toBeNull();
    });

    it('returns authenticated user from user property', function (): void {
        $component = Livewire::test(TwoFactor::class);

        expect($component->get('user')->id)->toBe($this->user->id);
    });
})->group('two-factor', 'livewire');
