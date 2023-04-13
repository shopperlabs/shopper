<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Framework\Http\Controllers\Api\BrandController;

/*
|--------------------------------------------------------------------------
| Backend API Routes
|--------------------------------------------------------------------------
|
|
*/

Route::get('/brands', BrandController::class)->name('brands');
