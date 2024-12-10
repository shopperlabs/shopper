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
Route::get('/payments', config('shopper.components.setting.pages.payment'))->name('payments');
Route::get('/zones', config('shopper.components.setting.pages.zones'))->name('zones');

Route::prefix('team')->group(function (): void {
    Route::get('/', config('shopper.components.setting.pages.team-index'))->name('users');
    Route::get('/roles/{role}', config('shopper.components.setting.pages.team-roles'))->name('users.role');
});
