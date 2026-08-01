<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Store\LegalController;
use Shopper\Api\Http\Controllers\Store\SettingController;

Route::get('/settings', SettingController::class);
Route::get('/legals', [LegalController::class, 'index']);
Route::get('/legals/{slug}', [LegalController::class, 'show']);
