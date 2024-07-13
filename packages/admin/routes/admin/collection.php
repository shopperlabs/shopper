<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', config('shopper.components.collection.pages.collection-index'))->name('index');
Route::get('/{collection}/edit', config('shopper.components.collection.pages.collection-edit'))->name('edit');
