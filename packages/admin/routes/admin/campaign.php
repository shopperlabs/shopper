<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::livewire('/', config('shopper.components.campaign.pages.campaign-index'))->name('index');
Route::livewire('/create', config('shopper.components.campaign.pages.campaign-create'))->name('create');
Route::livewire('/{record}/edit', config('shopper.components.campaign.pages.campaign-edit'))->name('edit');
