<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\Auth\PasskeyLoginController;
use Shopper\Livewire\Pages\Auth\ForgotPassword;
use Shopper\Livewire\Pages\Auth\Login;
use Shopper\Livewire\Pages\Auth\ResetPassword;

Route::redirect('/', shopper()->prefix().'/login', 301);

Route::livewire('/login', Login::class)->name('login');

if (config('shopper.auth.passkeys_enabled')) {
    Route::middleware('throttle:6,1')->group(function (): void {
        Route::get('/passkeys/login/options', [PasskeyLoginController::class, 'options'])
            ->name('passkeys.login-options');
        Route::post('/passkeys/login', [PasskeyLoginController::class, 'login'])
            ->name('passkeys.login');
    });
}

if (config('shopper.auth.password_reset', true)) {
    Route::livewire('/password/reset', ForgotPassword::class)->name('password.request');
    Route::livewire('/password/reset/{token}', ResetPassword::class)->name('password.reset');
}
