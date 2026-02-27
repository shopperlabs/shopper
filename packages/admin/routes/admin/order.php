<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', config('shopper.components.order.pages.order-index'))->name('index');
Route::get('/shipments', config('shopper.components.order.pages.order-shipments'))->name('shipments');
Route::get('/abandoned-carts', config('shopper.components.order.pages.order-abandoned-carts'))->name('abandoned-carts');
Route::get('/{order}/detail', config('shopper.components.order.pages.order-detail'))->name('detail');
