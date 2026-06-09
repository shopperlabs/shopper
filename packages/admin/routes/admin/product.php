<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Feature;

Route::as('products.')->group(function (): void {
    Route::get('/', config('shopper.components.product.pages.product-index'))->name('index');

    Route::middleware('can:products.edit')->group(function (): void {
        Route::get('/{product}/edit', config('shopper.components.product.pages.product-overview'))->name('edit');
        Route::get('/{product}/edit/media', config('shopper.components.product.pages.product-media'))->name('edit.media');
        Route::get('/{product}/edit/attributes', config('shopper.components.product.pages.product-attributes'))->name('edit.attributes');
        Route::get('/{product}/edit/variants', config('shopper.components.product.pages.product-variants'))->name('edit.variants');
        Route::get('/{product}/edit/variants/{variant}', config('shopper.components.product.pages.variant-edit'))->name('variant');
        Route::get('/{product}/edit/inventory', config('shopper.components.product.pages.product-inventory'))->name('edit.inventory');
        Route::get('/{product}/edit/pricing', config('shopper.components.product.pages.product-pricing'))->name('edit.pricing');
        Route::get('/{product}/edit/shipping', config('shopper.components.product.pages.product-shipping'))->name('edit.shipping');
        Route::get('/{product}/edit/files', config('shopper.components.product.pages.product-files'))->name('edit.files');
        Route::get('/{product}/edit/seo', config('shopper.components.product.pages.product-seo'))->name('edit.seo');
        Route::get('/{product}/edit/related', config('shopper.components.product.pages.product-related'))->name('edit.related');
    });
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
