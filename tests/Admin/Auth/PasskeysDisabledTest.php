<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Shopper\Livewire\Pages\Auth\Login;

uses(Tests\Admin\TestCase::class);

describe('Passkeys disabled', function (): void {
    it('does not register the shopper passkey routes', function (): void {
        expect(Route::has('shopper.passkeys.login-options'))->toBeFalse()
            ->and(Route::has('shopper.passkeys.login'))->toBeFalse()
            ->and(Route::has('shopper.passkeys.registration-options'))->toBeFalse()
            ->and(Route::has('shopper.passkeys.store'))->toBeFalse();
    });

    it('leaves the vendor passkey routes untouched for the host application', function (): void {
        expect(Route::has('passkey.login'))->toBeTrue()
            ->and(Route::has('passkey.login-options'))->toBeTrue()
            ->and(Route::has('passkey.store'))->toBeTrue();
    });

    it('does not show the passkey button on the login page', function (): void {
        Livewire::test(Login::class)
            ->assertDontSee(__('shopper::pages/auth.login.passkey_action'));
    });
});
