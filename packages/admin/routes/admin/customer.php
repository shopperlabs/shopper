<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', config('shopper.components.customer.pages.customer-index'))->name('index');
Route::get('/create', config('shopper.components.customer.pages.customer-create'))->name('create');
Route::get('/{user}/show', config('shopper.components.customer.pages.customer-show'))->name('show');
