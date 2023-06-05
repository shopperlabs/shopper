<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\Auth\TwoFactorAuthenticatedController;
use Shopper\Http\Livewire\Pages\Auth\ForgotPassword;
use Shopper\Http\Livewire\Pages\Auth\Login;
use Shopper\Http\Livewire\Pages\Auth\ResetPassword;

Route::redirect('/', shopper_prefix() . '/login', 301);

// Authentication...
Route::get('/login', Login::class)->name('login');
Route::get('/password/reset', ForgotPassword::class)->name('password.request');
Route::get('/password/reset/{token}', ResetPassword::class)->name('password.reset');

// Two Factor Authentication...
if (config('shopper.auth.2fa_enabled')) {
    Route::get('/two-factor-login', [TwoFactorAuthenticatedController::class, 'create'])
        ->name('two-factor.login');

    Route::post('/two-factor-login', [TwoFactorAuthenticatedController::class, 'store'])
        ->name('two-factor.post-login');
}
