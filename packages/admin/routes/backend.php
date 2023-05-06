<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Framework\Http\Controllers\DashboardController;
use Shopper\Framework\Http\Controllers\Ecommerce\ProductController;

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
    include __DIR__ . '/setting.php';
});
