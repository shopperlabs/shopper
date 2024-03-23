<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Feature;

Route::get('/dashboard', config('shopper.components.dashboard'))->name('dashboard');
Route::view('/profile', 'shopper::pages.account.profile')->name('profile');

Route::prefix('setting')->as('settings.')->group(function (): void {
    require __DIR__ . '/admin/setting.php';
});

if (Feature::enabled('brand')) {
    Route::as('brands.')->group(function (): void {
        require __DIR__ . '/admin/brand.php';
    });
}

if (Feature::enabled('category')) {
    Route::as('categories.')->group(function (): void {
        require __DIR__ . '/admin/category.php';
    });
}

if (Feature::enabled('collection')) {
    Route::as('collections.')->group(function (): void {
        require __DIR__ . '/admin/collection.php';
    });
}

if (Feature::enabled('customer')) {
    Route::as('customers.')->group(function (): void {
        require __DIR__ . '/admin/customer.php';
    });
}

if (Feature::enabled('discount')) {
    Route::as('discounts.')->group(function (): void {
        require __DIR__ . '/admin/discount.php';
    });
}

if (Feature::enabled('order')) {
    Route::as('orders.')->group(function (): void {
        require __DIR__ . '/admin/order.php';
    });
}

if (Feature::enabled('product')) {
    Route::as('products.')->group(function (): void {
        require __DIR__ . '/admin/product.php';
    });
}

if (Feature::enabled('review')) {
    Route::as('reviews.')->group(function (): void {
        require __DIR__ . '/admin/review.php';
    });
}
