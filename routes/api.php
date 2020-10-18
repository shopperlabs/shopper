<?php

use Illuminate\Support\Facades\Route;
use Shopper\Framework\Http\Controllers\Api\Settings\SettingController;
          
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// shop initialization
Route::put('/configuration', [SettingController::class, 'general']);

// settings page
Route::prefix('settings')->group(function () {
    Route::put('/general',   [SettingController::class, 'general']);
    Route::put('/analytics', [SettingController::class, 'analytics']);
});