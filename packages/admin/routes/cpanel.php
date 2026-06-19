<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Feature;

Route::livewire('/dashboard', config('shopper.components.dashboard.pages.dashboard'))->name('dashboard');
Route::livewire('/profile', config('shopper.components.account.pages.account-index'))->name('profile');

Route::prefix('setting')->as('settings.')->group(function (): void {
    require __DIR__.'/admin/setting.php';
});

Route::as('customers.')->prefix('customers')->group(function (): void {
    require __DIR__.'/admin/customer.php';
});

Route::as('orders.')->prefix('orders')->group(function (): void {
    require __DIR__.'/admin/order.php';
});

Route::prefix('products')->group(function (): void {
    require __DIR__.'/admin/product.php';
});

if (Feature::enabled('brand')) {
    Route::livewire('/brands', config('shopper.components.brand.pages.brand-index'))
        ->name('brands.index');
}

if (Feature::enabled('category')) {
    Route::livewire('/categories', config('shopper.components.category.pages.category-index'))
        ->name('categories.index');
}

if (Feature::enabled('collection')) {
    Route::as('collections.')->prefix('collections')->group(function (): void {
        require __DIR__.'/admin/collection.php';
    });
}

if (Feature::enabled('campaign')) {
    Route::as('campaigns.')->prefix('campaigns')->group(function (): void {
        require __DIR__.'/admin/campaign.php';
    });
}

if (Feature::enabled('discount')) {
    Route::as('discounts.')->prefix('discounts')->group(function (): void {
        require __DIR__.'/admin/discount.php';
    });
}

if (Feature::enabled('review')) {
    Route::livewire('/reviews', config('shopper.components.review.pages.review-index'))
        ->name('reviews.index');
}
