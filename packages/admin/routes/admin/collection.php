<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::livewire('/', config('shopper.components.collection.pages.collection-index'))->name('index');
Route::livewire('/{collection}/edit', config('shopper.components.collection.pages.collection-edit'))->name('edit');
