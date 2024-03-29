<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Feature;

Route::get('/dashboard', config('shopper.components.dashboard.pages.dashboard'))->name('dashboard');
Route::get('/profile', config('shopper.components.account.pages.account-index'))->name('profile');

Route::prefix('setting')->as('settings.')->group(function (): void {
    require __DIR__ . '/admin/setting.php';
});

if (Feature::enabled('brand')) {
    Route::get('/brands', config('shopper.components.brand.pages.brand-index'))
        ->name('brands.index');
}

if (Feature::enabled('category')) {
    Route::get('/categories', config('shopper.components.category.pages.category-index'))
        ->name('categories.index');
}

if (Feature::enabled('collection')) {
    Route::as('collections.')->prefix('collections')->group(function (): void {
        require __DIR__ . '/admin/collection.php';
    });
}

if (Feature::enabled('customer')) {
    Route::as('customers.')->prefix('customers')->group(function (): void {
        require __DIR__ . '/admin/customer.php';
    });
}

if (Feature::enabled('discount')) {
    Route::as('discounts.')->prefix('discounts')->group(function (): void {
        require __DIR__ . '/admin/discount.php';
    });
}

if (Feature::enabled('order')) {
    Route::as('orders.')->prefix('orders')->group(function (): void {
        require __DIR__ . '/admin/order.php';
    });
}

if (Feature::enabled('product')) {
    Route::prefix('products')->group(function (): void {
        require __DIR__ . '/admin/product.php';
    });
}

if (Feature::enabled('review')) {
    Route::as('reviews.')->prefix('reviews')->group(function (): void {
        require __DIR__ . '/admin/review.php';
    });
}
