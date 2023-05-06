<?php

declare(strict_types=1);

namespace Shopper\Framework;

use Illuminate\Support\Facades\Route;

final class Shopper
{
    public static function version(): string
    {
        return '1.1';
    }

    public static function prefix(): string
    {
        return config('shopper.routes.prefix', 'shopper');
    }

    public static function username(): string
    {
        return config('shopper.auth.username', 'email');
    }

    public function initializeRoute(): self
    {
        Route::namespace('Shopper\Framework\Http\Controllers')
            ->middleware(['shopper', 'shopper.setup'])
            ->as('shopper.')
            ->prefix(self::prefix())
            ->group(function () {
                Route::get('/configuration', 'SettingController@initialize')->name('initialize');
            });

        return $this;
    }
}
