<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Feature;

Route::get('/dashboard', config('shopper.components.dashboard.pages.dashboard'))->name('dashboard');
Route::get('/profile', config('shopper.components.account.pages.account-index'))->name('profile');

Route::prefix('setting')->as('settings.')->group(function (): void {
    require __DIR__ . '/admin/setting.php';
});

Route::as('customers.')->prefix('customers')->group(function (): void {
    require __DIR__ . '/admin/customer.php';
});

Route::as('orders.')->prefix('orders')->group(function (): void {
    require __DIR__ . '/admin/order.php';
});

Route::prefix('products')->group(function (): void {
    require __DIR__ . '/admin/product.php';
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

if (Feature::enabled('discount')) {
    Route::get('/discounts', config('shopper.components.discount.pages.discount-index'))
        ->name('discounts.index');
}

if (Feature::enabled('review')) {
    Route::get('/reviews', config('shopper.components.review.pages.review-index'))
        ->name('reviews.index');
}
