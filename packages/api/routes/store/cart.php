<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Cart\CartAddressController;
use Shopper\Http\Enum\RateLimit;
use Shopper\Api\Http\Controllers\Cart\CartController;
use Shopper\Api\Http\Controllers\Cart\CartLineController;
use Shopper\Api\Http\Controllers\Cart\CartPaymentMethodController;
use Shopper\Api\Http\Controllers\Cart\CartShippingMethodController;
use Shopper\Api\Http\Controllers\Cart\CompleteCartController;
use Shopper\Api\Http\Controllers\Cart\PaymentSessionController;
use Shopper\Api\Http\Controllers\Cart\ShippingOptionController;

Route::post('/carts', [CartController::class, 'store']);
Route::get('/carts/{cartId}', [CartController::class, 'show']);
Route::post('/carts/{cartId}/lines', [CartLineController::class, 'store']);
Route::patch('/carts/{cartId}/lines/{lineId}', [CartLineController::class, 'update']);
Route::delete('/carts/{cartId}/lines/{lineId}', [CartLineController::class, 'destroy']);
Route::post('/carts/{cartId}/addresses', [CartAddressController::class, 'store']);
Route::get('/carts/{cartId}/shipping-options', ShippingOptionController::class);
Route::post('/carts/{cartId}/shipping-method', [CartShippingMethodController::class, 'store']);
Route::get('/carts/{cartId}/payment-methods', [CartPaymentMethodController::class, 'index']);
Route::post('/carts/{cartId}/payment-method', [CartPaymentMethodController::class, 'store']);
Route::post('/carts/{cartId}/payment-session', [PaymentSessionController::class, 'store'])
    ->middleware('throttle:'.RateLimit::Checkout->value);
Route::post('/carts/{cartId}/complete', CompleteCartController::class)
    ->middleware('throttle:'.RateLimit::Checkout->value);
