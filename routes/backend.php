<?php

use Illuminate\Support\Facades\Route;
use Shopper\Framework\Http\Controllers\Ecommerce\ProductImageController;
use Shopper\Framework\Http\Controllers\ProfileController;
use Shopper\Framework\Http\Controllers\SettingController;
use Shopper\Framework\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| Backend Web Routes
|--------------------------------------------------------------------------
|
|
*/

Route::redirect('/', shopper_prefix() . '/dashboard', 301);
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::prefix('profile')->group(function () {
    Route::get('/{section?}', [ProfileController::class, 'profile'])->name('profile');
});

Route::namespace('Ecommerce')->group(function () {
    Route::resource('categories', 'CategoryController');
    Route::resource('brands', 'BrandController');
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
    Route::view('/management', 'shopper::pages.settings.management')->name('users');
    Route::view('/analytics', 'shopper::pages.settings.analytics')->name('analytics');
    Route::view('/integrations', 'shopper::pages.settings.integrations')->name('integrations');

    Route::get('/shop', [ShopController::class, 'setting'])->name('shop');
    Route::put('/update/{store}', [ShopController::class, 'update'])->name('shop.update');
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::resource('inventories', 'InventoryController');
});
