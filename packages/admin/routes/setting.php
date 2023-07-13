<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\InventoryController;
use Shopper\Http\Controllers\SettingController;

Route::view('/', 'shopper::pages.settings.index')->name('index');
Route::view('/legal', 'shopper::pages.settings.legal')->name('legal');
Route::view('/management', 'shopper::pages.settings.management.index')->name('users');
Route::view('/management/user/new', 'shopper::pages.settings.management.create')->name('users.new');
Route::get('/management/roles/{role}', [SettingController::class, 'role'])->name('users.role');
Route::view('/analytics', 'shopper::pages.settings.analytics')->name('analytics');
Route::view('/payments', 'shopper::pages.settings.payments.general')->name('payments');
Route::view('/general', 'shopper::pages.settings.general')->name('shop');

Route::resource('inventories', InventoryController::class);
