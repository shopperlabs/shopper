<?php

use Illuminate\Support\Facades\Route;
use Shopper\Framework\Http\Controllers\Api\SettingController;

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

Route::prefix('configuration')->group(function () {
    Route::post('/', [SettingController::class, 'configure']);
});