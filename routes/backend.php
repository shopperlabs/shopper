<?php

use Illuminate\Support\Facades\Route;
use Shopper\Framework\Http\Controllers\Ecommerce\ProductImageController;
use Shopper\Framework\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Backend Web Routes
|--------------------------------------------------------------------------
|
|
*/

Route::redirect('/', shopper_prefix() . '/dashboard', 301);
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::view('/profile', 'shopper::pages.account.profile')->name('profile');

Route::namespace('Ecommerce')->group(function () {
    Route::resource('brands', 'BrandController');
    Route::resource('categories', 'CategoryController');
    Route::resource('collections', 'CollectionController');
    Route::resource('products', 'ProductController');
    Route::post('/product/images/upload/{id}', [ProductImageController::class, 'store'])->name('products.upload');
    Route::get('/product/images/{id}', [ProductImageController::class, 'index'])->name('products.images');
    Route::delete('/product/image/remove/{id}', [ProductImageController::class, 'delete'])->name('products.image.remove');
});

Route::resource('customers', 'CustomerController');
Route::resource('reviews', 'ReviewController');
Route::resource('discounts', 'DiscountController');
Route::resource('inventory-histories', 'InventoryHistoryController');

Route::prefix('setting')->as('settings.')->group(function () {
    Route::view('/', 'shopper::pages.settings.index')->name('index');
    Route::view('/management', 'shopper::pages.settings.management.index')->name('users');
    Route::view('/management/user/new', 'shopper::pages.settings.management.create')->name('user.new');
    Route::get('/management/roles/{role}', [SettingController::class, 'role'])->name('user.role');
    Route::view('/analytics', 'shopper::pages.settings.analytics')->name('analytics');
    Route::view('/integrations', 'shopper::pages.settings.integrations')->name('integrations');
    Route::view('/payments', 'shopper::pages.settings.payments.general')->name('payments');
    Route::view('/general', 'shopper::pages.settings.general')->name('shop');
    Route::resource('inventories', 'InventoryController');
});
