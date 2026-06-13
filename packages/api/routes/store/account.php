<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Account\AddressController;
use Shopper\Api\Http\Controllers\Account\AvatarController;
use Shopper\Api\Http\Controllers\Account\CustomerController;
use Shopper\Api\Http\Controllers\Account\OrderController;
use Shopper\Api\Http\Controllers\Auth\AuthenticationController;

Route::post('/auth/logout', [AuthenticationController::class, 'logout']);

Route::prefix('customers/me')->group(function (): void {
    Route::get('/', [CustomerController::class, 'show']);
    Route::patch('/', [CustomerController::class, 'update']);
    Route::post('/avatar', [AvatarController::class, 'store']);
    Route::delete('/avatar', [AvatarController::class, 'destroy']);
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::patch('/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{orderId}', [OrderController::class, 'show']);
});
