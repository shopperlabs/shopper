<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Auth\AuthenticationController;
use Shopper\Api\Http\Controllers\Auth\PasswordResetController;
use Shopper\Http\Enum\RateLimit;

Route::prefix('auth')->middleware('throttle:'.RateLimit::Auth->value)->group(function (): void {
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/forgot-password', [PasswordResetController::class, 'send']);
    Route::post('/reset-password', [PasswordResetController::class, 'reset']);
});
