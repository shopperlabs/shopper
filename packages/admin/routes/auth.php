<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Livewire\Pages\Auth\ForgotPassword;
use Shopper\Livewire\Pages\Auth\Login;
use Shopper\Livewire\Pages\Auth\ResetPassword;

Route::redirect('/', shopper()->prefix().'/login', 301);

Route::get('/login', Login::class)->name('login');

if (config('shopper.auth.password_reset', true)) {
    Route::get('/password/reset', ForgotPassword::class)->name('password.request');
    Route::get('/password/reset/{token}', ResetPassword::class)->name('password.reset');
}
