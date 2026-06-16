<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', config('shopper.components.campaign.pages.campaign-index'))->name('index');
Route::get('/create', config('shopper.components.campaign.pages.campaign-create'))->name('create');
Route::get('/{record}/edit', config('shopper.components.campaign.pages.campaign-edit'))->name('edit');
