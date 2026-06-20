<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Feature;

Route::as('products.')->group(function (): void {
    Route::livewire('/', config('shopper.components.product.pages.product-index'))->name('index');

    Route::middleware('can:products.edit')->group(function (): void {
        Route::livewire('/{product}/edit', config('shopper.components.product.pages.product-overview'))->name('edit');
        Route::livewire('/{product}/edit/media', config('shopper.components.product.pages.product-media'))->name('edit.media');
        Route::livewire('/{product}/edit/attributes', config('shopper.components.product.pages.product-attributes'))->name('edit.attributes');
        Route::livewire('/{product}/edit/variants', config('shopper.components.product.pages.product-variants'))->name('edit.variants');
        Route::livewire('/{product}/edit/variants/{variant}', config('shopper.components.product.pages.variant-edit'))->name('variant');
        Route::livewire('/{product}/edit/inventory', config('shopper.components.product.pages.product-inventory'))->name('edit.inventory');
        Route::livewire('/{product}/edit/pricing', config('shopper.components.product.pages.product-pricing'))->name('edit.pricing');
        Route::livewire('/{product}/edit/shipping', config('shopper.components.product.pages.product-shipping'))->name('edit.shipping');
        Route::livewire('/{product}/edit/files', config('shopper.components.product.pages.product-files'))->name('edit.files');
        Route::livewire('/{product}/edit/seo', config('shopper.components.product.pages.product-seo'))->name('edit.seo');
        Route::livewire('/{product}/edit/related', config('shopper.components.product.pages.product-related'))->name('edit.related');
    });
});

if (Feature::enabled('attribute')) {
    Route::livewire('attributes', config('shopper.components.product.pages.attribute-index'))
        ->name('attributes.index');
}

if (Feature::enabled('supplier')) {
    Route::livewire('suppliers', config('shopper.components.product.pages.supplier-index'))
        ->name('suppliers.index');
}

if (Feature::enabled('tag')) {
    Route::livewire('tags', config('shopper.components.product.pages.tag-index'))
        ->name('tags.index');
}
