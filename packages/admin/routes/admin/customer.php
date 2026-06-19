<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::livewire('/', config('shopper.components.customer.pages.customer-index'))->name('index');
Route::livewire('/create', config('shopper.components.customer.pages.customer-create'))->name('create');
Route::livewire('/{user}/show', config('shopper.components.customer.pages.customer-show'))->name('show');
