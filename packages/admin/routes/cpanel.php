<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\DiscountController;
use Shopper\Http\Controllers\Ecommerce;
use Shopper\Http\Controllers\InventoryHistoryController;
use Shopper\Http\Controllers\ReviewController;

Route::get('/dashboard', config('shopper.components.dashboard'))->name('dashboard');
Route::view('/profile', 'shopper::pages.account.profile')->name('profile');

Route::resource('brands', Ecommerce\BrandController::class);
Route::resource('categories', Ecommerce\CategoryController::class);
Route::resource('collections', Ecommerce\CollectionController::class);
Route::resource('customers', Ecommerce\CustomerController::class);
Route::resource('products', Ecommerce\ProductController::class);
Route::get('/products/{product}/variants/{id}', [Ecommerce\ProductController::class, 'variant'])->name('products.variant');
Route::resource('orders', Ecommerce\OrderController::class)->only(['index', 'show', 'create']);

Route::resource('reviews', ReviewController::class);
Route::resource('discounts', DiscountController::class);
Route::resource('inventory-histories', InventoryHistoryController::class);

Route::prefix('setting')->as('settings.')->group(function (): void {
    require __DIR__ . '/setting.php';
});
