<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::livewire('/', config('shopper.components.order.pages.order-index'))->name('index');
Route::livewire('/shipments', config('shopper.components.order.pages.order-shipments'))->name('shipments');
Route::livewire('/abandoned-carts', config('shopper.components.order.pages.order-abandoned-carts'))->name('abandoned-carts');
Route::livewire('/{order}/detail', config('shopper.components.order.pages.order-detail'))->name('detail');
