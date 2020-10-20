<?php

namespace Shopper\Framework;

use Illuminate\Support\Facades\Route;

class Shopper
{
    /**
     * Get the current Shopper version.
     *
     * @return string
     */
    public static function version()
    {
        return '2.0.0';
    }

    /**
     * Get the URI path prefix utilized by Shopper.
     *
     * @return string
     */
    public static function prefix()
    {
        return config('shopper.routes.prefix');
    }

    /**
     * Register the Shop routes.
     *
     * @return Shopper
     */
    public function initializeRoute()
    {
        Route::namespace('Shopper\Framework\Http\Controllers')
            ->middleware('shopper')
            ->as('shopper.')
            ->prefix(self::prefix())
            ->group(function () {
                Route::get('/configuration', 'SettingController@initialize')->name('initialize');
            });

        return $this;
    }
}
