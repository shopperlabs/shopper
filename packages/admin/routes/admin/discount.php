<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::livewire('/', config('shopper.components.discount.pages.discount-index'))->name('index');
Route::livewire('/{record}/edit', config('shopper.components.discount.pages.discount-edit'))->name('edit');
