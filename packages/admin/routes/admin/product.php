<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::as('products.')->group(function (): void {
    Route::get('/', config('shopper.components.product.pages.product-index'))->name('index');
    Route::get('/{product}/edit', config('shopper.components.product.pages.product-edit'))->name('edit');
    Route::get('/{productId}/variants/{variantId}', config('shopper.components.product.pages.variant-edit'))
        ->name('variant');
});

if (\Shopper\Feature::enabled('attribute')) {
    Route::get('attributes', config('shopper.components.product.pages.attribute-index'))
        ->name('attributes.index');
}
