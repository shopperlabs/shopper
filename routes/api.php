<?php

use Illuminate\Support\Facades\Route;
use Shopper\Framework\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| Backend API Routes
|--------------------------------------------------------------------------
|
|
*/

Route::get('/brands', Api\BrandController::class)->name('brands');
