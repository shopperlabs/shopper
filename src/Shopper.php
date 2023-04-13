<?php

declare(strict_types=1);

namespace Shopper\Framework;

use Illuminate\Support\Facades\Route;

class Shopper
{
    /**
     * Get the current Shopper version.
     */
    public static function version(): string
    {
        return '1.0.7';
    }

    /**
     * Get the URI path prefix utilized by Shopper.
     */
    public static function prefix(): string
    {
        return config('shopper.routes.prefix', 'shopper');
    }

    /**
     * Get the username used for authentication.
     */
    public static function username(): string
    {
        return config('shopper.auth.username', 'email');
    }

    /**
     * Register the Shop routes.
     */
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
