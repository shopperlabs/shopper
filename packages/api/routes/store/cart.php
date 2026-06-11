<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Cart\CartController;
use Shopper\Api\Http\Controllers\Cart\CartLineController;

Route::post('/carts', [CartController::class, 'store']);
Route::get('/carts/{cartId}', [CartController::class, 'show']);
Route::post('/carts/{cartId}/lines', [CartLineController::class, 'store']);
Route::patch('/carts/{cartId}/lines/{lineId}', [CartLineController::class, 'update']);
Route::delete('/carts/{cartId}/lines/{lineId}', [CartLineController::class, 'destroy']);
