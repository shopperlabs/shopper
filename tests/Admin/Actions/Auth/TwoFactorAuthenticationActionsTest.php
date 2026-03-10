<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Actions\Auth\DisableTwoFactorAuthentication;
use Shopper\Actions\Auth\EnableTwoFactorAuthentication;
use Shopper\Actions\Auth\GenerateNewRecoveryCodes;
use Shopper\Events\TwoFactor\TwoFactorAuthenticationEnabled;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe('EnableTwoFactorAuthentication', function (): void {
    it('can enable two factor authentication', function (): void {
        Event::fake([TwoFactorAuthenticationEnabled::class]);

        $user = User::factory()->create();
        $action = app(EnableTwoFactorAuthentication::class);

        $action($user);

        expect($user->two_factor_secret)->not->toBeNull()
            ->and($user->two_factor_recovery_codes)->not->toBeNull();

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        expect($recoveryCodes)->toHaveCount(8);

        Event::assertDispatched(TwoFactorAuthenticationEnabled::class);
    });

    it('generates encrypted secret and recovery codes', function (): void {
        $user = User::factory()->create();
        $action = app(EnableTwoFactorAuthentication::class);

        $action($user);

        $secret = decrypt($user->two_factor_secret);
        expect($secret)->toBeString()->not->toBeEmpty();

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        expect($recoveryCodes)->toBeArray();

        foreach ($recoveryCodes as $code) {
            expect($code)->toMatch('/^[a-zA-Z0-9]{10}-[a-zA-Z0-9]{10}$/');
        }
    });
})->group('two-factor', 'actions');

describe('DisableTwoFactorAuthentication', function (): void {
    it('can disable two factor authentication', function (): void {
        $user = User::factory()->create([
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        ]);

        $action = app(DisableTwoFactorAuthentication::class);
        $action($user);

        expect($user->fresh()->two_factor_secret)->toBeNull()
            ->and($user->fresh()->two_factor_recovery_codes)->toBeNull();
    });
})->group('two-factor', 'actions');

describe('GenerateNewRecoveryCodes', function (): void {
    it('can generate new recovery codes', function (): void {
        $user = User::factory()->create([
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['old-code'])),
        ]);

        $oldCodes = $user->two_factor_recovery_codes;

        $action = app(GenerateNewRecoveryCodes::class);
        $action($user);

        expect($user->fresh()->two_factor_recovery_codes)->not->toBe($oldCodes);

        $newCodes = json_decode(decrypt($user->fresh()->two_factor_recovery_codes), true);
        expect($newCodes)->toHaveCount(8);
    });

    it('keeps the two factor secret when regenerating codes', function (): void {
        $user = User::factory()->create([
            'two_factor_secret' => encrypt('original-secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['old-code'])),
        ]);

        $originalSecret = $user->two_factor_secret;

        $action = app(GenerateNewRecoveryCodes::class);
        $action($user);

        expect($user->fresh()->two_factor_secret)->toBe($originalSecret);
    });
})->group('two-factor', 'actions');
