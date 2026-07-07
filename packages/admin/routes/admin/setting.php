<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::livewire('/general', config('shopper.components.setting.pages.general'))->name('shop');
Route::livewire('/appearance', config('shopper.components.setting.pages.appearance'))->name('appearance');

Route::prefix('locations')->group(function (): void {
    Route::livewire('/', config('shopper.components.setting.pages.location-index'))->name('locations');
    Route::livewire('/create', config('shopper.components.setting.pages.location-create'))->name('locations.create');
    Route::livewire('/{inventory}/edit', config('shopper.components.setting.pages.location-edit'))->name('locations.edit');
});

Route::livewire('/legal', config('shopper.components.setting.pages.legal'))->name('legal');
Route::livewire('/analytics', config('shopper.components.setting.pages.analytics'))->name('analytics');
Route::livewire('/channels', config('shopper.components.setting.pages.channels'))->name('channels');
Route::livewire('/payment-methods', config('shopper.components.setting.pages.payment-methods'))->name('payment-methods');
Route::livewire('/carriers', config('shopper.components.setting.pages.carriers'))->name('carriers');
Route::livewire('/zones', config('shopper.components.setting.pages.zones'))->name('zones');
Route::livewire('/taxes', config('shopper.components.setting.pages.taxes'))->name('taxes');
Route::livewire('/currencies', config('shopper.components.setting.pages.currencies'))->name('currencies');
Route::livewire('/webhooks', config('shopper.components.setting.pages.webhooks'))->name('webhooks');

Route::prefix('team')->group(function (): void {
    Route::livewire('/', config('shopper.components.setting.pages.team-index'))->name('users');
    Route::livewire('/roles/{role}', config('shopper.components.setting.pages.team-roles'))->name('users.role');
});
