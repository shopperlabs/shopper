<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Geo\CountryController;
use Shopper\Api\Http\Controllers\Geo\CurrencyController;
use Shopper\Api\Http\Controllers\Geo\ZoneController;

Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/{code}', [CountryController::class, 'show']);
Route::get('/zones', [ZoneController::class, 'index']);
Route::get('/zones/{code}', [ZoneController::class, 'show']);
Route::get('/currencies', [CurrencyController::class, 'index']);
Route::get('/currencies/{code}', [CurrencyController::class, 'show']);
