<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Framework\Http\Controllers\DashboardController;
use Shopper\Framework\Http\Controllers\Ecommerce\ProductController;
use Shopper\Framework\Http\Controllers\SettingController;
use Shopper\Framework\Http\Controllers\TemplatesController;

Route::get('/dashboard', DashboardController::class)->name('dashboard');
Route::view('/profile', 'shopper::pages.account.profile')->name('profile');

Route::namespace('Ecommerce')->group(function () {
    Route::resource('brands', 'BrandController');
    Route::resource('categories', 'CategoryController');
    Route::resource('collections', 'CollectionController');
    Route::resource('customers', 'CustomerController');
    Route::resource('products', 'ProductController');
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/{product}/variants/{id}', [ProductController::class, 'variant'])->name('variant');
    });
    Route::resource('orders', 'OrderController')->only(['index', 'show', 'create']);
});

Route::resource('reviews', 'ReviewController');
Route::resource('discounts', 'DiscountController');
Route::resource('inventory-histories', 'InventoryHistoryController');

Route::prefix('setting')->as('settings.')->group(function () {
    Route::view('/', 'shopper::pages.settings.index')->name('index');
    Route::view('/management', 'shopper::pages.settings.management.index')->name('users');
    Route::view('/legal', 'shopper::pages.settings.legal')->name('legal');
    Route::view('/management/user/new', 'shopper::pages.settings.management.create')->name('user.new');
    Route::get('/management/roles/{role}', [SettingController::class, 'role'])->name('user.role');
    Route::view('/analytics', 'shopper::pages.settings.analytics')->name('analytics');
    Route::view('/payments', 'shopper::pages.settings.payments.general')->name('payments');
    Route::view('/general', 'shopper::pages.settings.general')->name('shop');
    Route::resource('inventories', 'InventoryController');
    Route::resource('attributes', 'AttributeController')->except('destroy', 'store', 'update');

    Route::prefix('email-setting')->group(function () {
        Route::view('/', 'shopper::pages.settings.mails.index')->name('mails');
        Route::view('/templates/select', 'shopper::pages.settings.mails.templates.add-template')->name('mails.select-template');
        Route::get('/templates/create/{type}/{name}/{skeleton}', [TemplatesController::class, 'create'])->name('mails.create-template');
        Route::post('/templates/create', [TemplatesController::class, 'store'])->name('mails.store-template');
    });

    Route::prefix('integrations')->group(function () {
        Route::view('/', 'shopper::pages.settings.integrations.index')->name('integrations');
        Route::view('/github', 'shopper::pages.settings.integrations.github')->name('integrations.github');
        Route::view('/twitter', 'shopper::pages.settings.integrations.twitter')->name('integrations.twitter');
    });
});
