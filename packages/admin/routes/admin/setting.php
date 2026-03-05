<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', config('shopper.components.setting.pages.setting-index'))->name('index');
Route::get('/general', config('shopper.components.setting.pages.general'))->name('shop');

Route::prefix('locations')->group(function (): void {
    Route::get('/', config('shopper.components.setting.pages.location-index'))->name('locations');
    Route::get('/create', config('shopper.components.setting.pages.location-create'))->name('locations.create');
    Route::get('/{inventory}/edit', config('shopper.components.setting.pages.location-edit'))->name('locations.edit');
});

Route::get('/legal', config('shopper.components.setting.pages.legal'))->name('legal');
Route::get('/analytics', config('shopper.components.setting.pages.analytics'))->name('analytics');
Route::get('/payment-methods', config('shopper.components.setting.pages.payment-methods'))->name('payment-methods');
Route::get('/carriers', config('shopper.components.setting.pages.carriers'))->name('carriers');
Route::get('/zones', config('shopper.components.setting.pages.zones'))->name('zones');
Route::get('/taxes', config('shopper.components.setting.pages.taxes'))->name('taxes');
Route::get('/currencies', config('shopper.components.setting.pages.currencies'))->name('currencies');

Route::prefix('team')->group(function (): void {
    Route::get('/', config('shopper.components.setting.pages.team-index'))->name('users');
    Route::get('/roles/{role}', config('shopper.components.setting.pages.team-roles'))->name('users.role');
});
