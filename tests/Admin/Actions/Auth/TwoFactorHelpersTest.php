<?php

declare(strict_types=1);

use PragmaRX\Google2FA\Google2FA;
use Shopper\Actions\Auth\RecoveryCode;
use Shopper\Concerns\TwoFactorAuthenticationProvider;

uses(Tests\Admin\TestCase::class);

describe(RecoveryCode::class, function (): void {
    it('generates recovery code in correct format', function (): void {
        $code = RecoveryCode::generate();

        expect($code)->toMatch('/^[a-zA-Z0-9]{10}-[a-zA-Z0-9]{10}$/');
    });

    it('generates unique recovery codes', function (): void {
        $codes = collect(range(1, 100))->map(fn (): string => RecoveryCode::generate());

        expect($codes->unique()->count())->toBe(100);
    });
})->group('two-factor', 'helpers');

describe(TwoFactorAuthenticationProvider::class, function (): void {
    it('generates a valid secret key', function (): void {
        $provider = app(TwoFactorAuthenticationProvider::class);
        $secret = $provider->generateSecretKey();

        expect($secret)->toBeString()
            ->and(mb_strlen($secret))->toBeGreaterThanOrEqual(16);
    });

    it('generates qr code url', function (): void {
        $provider = app(TwoFactorAuthenticationProvider::class);
        $google2fa = app(Google2FA::class);
        $secret = $google2fa->generateSecretKey();

        $url = $provider->qrCodeUrl('Test App', 'test@example.com', $secret);

        expect($url)->toBeString()
            ->and($url)->toContain('otpauth://totp/')
            ->and($url)->toContain('Test%20App')
            ->and($url)->toContain('test%40example.com');
    });

    it('verifies valid code', function (): void {
        $provider = app(TwoFactorAuthenticationProvider::class);
        $google2fa = app(Google2FA::class);
        $secret = $google2fa->generateSecretKey();
        $validCode = $google2fa->getCurrentOtp($secret);

        expect($provider->verify($secret, $validCode))->toBeTrue();
    });

    it('rejects invalid code', function (): void {
        $provider = app(TwoFactorAuthenticationProvider::class);
        $google2fa = app(Google2FA::class);
        $secret = $google2fa->generateSecretKey();

        expect($provider->verify($secret, 'invalid'))->toBeFalse();
    });
})->group('two-factor', 'provider');
