<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Order\OrderController;
use Shopper\Api\Http\Controllers\Order\ShipmentController;

Route::get('/orders/{orderId}', [OrderController::class, 'show']);
Route::get('/shipments/{shipmentId}', [ShipmentController::class, 'show']);
