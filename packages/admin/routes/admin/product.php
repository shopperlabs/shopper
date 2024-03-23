<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\AttributeController;
use Shopper\Http\Controllers\Ecommerce;

Route::as('products.')->group(function (): void {
    Route::get('/', config('shopper.components.product.page.product-index'))->name('index');
    Route::get('/create', config('shopper.components.product.page.product-create'))->name('create');
    Route::get('/{product}/variants/{id}', [Ecommerce\ProductController::class, 'variant'])
        ->name('variant');
});

Route::prefix('attributes')->as('attributes.')->group(function (): void {
    Route::get('/', config('shopper.components.product.page.attribute-index'))->name('index');
});
Route::resource('attributes', AttributeController::class)->except('destroy', 'store', 'update');
