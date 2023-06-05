<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\Auth\ForgotPasswordController;
use Shopper\Http\Controllers\Auth\LoginController;
use Shopper\Http\Controllers\Auth\ResetPasswordController;
use Shopper\Http\Controllers\Auth\TwoFactorAuthenticatedController;
use Shopper\Http\Livewire\Pages\Auth\Login;

Route::redirect('/', shopper_prefix() . '/login', 301);

// Authentication...
Route::get('/login', Login::class)->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('post.login');

// Password Reset...
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

// Two Factor Authentication...
if (config('shopper.auth.2fa_enabled')) {
    Route::get('/two-factor-login', [TwoFactorAuthenticatedController::class, 'create'])
        ->name('two-factor.login');

    Route::post('/two-factor-login', [TwoFactorAuthenticatedController::class, 'store'])
        ->name('two-factor.post-login');
}
