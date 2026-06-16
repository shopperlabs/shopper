<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', config('shopper.components.discount.pages.discount-index'))->name('index');
Route::get('/{record}/edit', config('shopper.components.discount.pages.discount-edit'))->name('edit');
