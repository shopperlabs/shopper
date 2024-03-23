<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\AttributeController;
use Shopper\Http\Controllers\Ecommerce;

Route::resource('products', Ecommerce\ProductController::class);
Route::prefix('products')->group(function (): void {
    Route::get('/products/{product}/variants/{id}', [Ecommerce\ProductController::class, 'variant'])
        ->name('products.variant');
    Route::resource('attributes', AttributeController::class)->except('destroy', 'store', 'update');
});
