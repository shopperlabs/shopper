<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Order\FileDownloadController;
use Shopper\Api\Http\Controllers\Order\OrderController;
use Shopper\Api\Http\Controllers\Order\ShipmentController;

Route::get('/orders/{orderId}', [OrderController::class, 'show']);
Route::get('/shipments/{shipmentId}', [ShipmentController::class, 'show']);
Route::get('/files/{fileId}', FileDownloadController::class)
    ->middleware('signed')
    ->name('shopper.store.files.download');
