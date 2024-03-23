<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\Ecommerce;

Route::resource('orders', Ecommerce\OrderController::class)->only(['index', 'show', 'create']);
