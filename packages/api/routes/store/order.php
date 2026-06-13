<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Order\OrderController;

Route::get('/orders/{orderId}', [OrderController::class, 'show']);
