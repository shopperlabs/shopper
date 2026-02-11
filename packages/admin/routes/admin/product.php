<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Feature;

Route::as('products.')->group(function (): void {
    Route::get('/', config('shopper.components.product.pages.product-index'))->name('index');
    Route::get('/{product}/edit', config('shopper.components.product.pages.product-edit'))->name('edit');
    Route::get('/{product}/variants/{variant}', config('shopper.components.product.pages.variant-edit'))
        ->name('variant');
});

if (Feature::enabled('attribute')) {
    Route::get('attributes', config('shopper.components.product.pages.attribute-index'))
        ->name('attributes.index');
}

if (Feature::enabled('supplier')) {
    Route::get('suppliers', config('shopper.components.product.pages.supplier-index'))
        ->name('suppliers.index');
}

if (Feature::enabled('tag')) {
    Route::get('tags', config('shopper.components.product.pages.tag-index'))
        ->name('tags.index');
}
