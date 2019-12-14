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
        return config('shopper.prefix');
    }

    /**
     * Register the Shop routes.
     *
     * @return Shopper
     */
    public function initializeRoute()
    {
        Route::namespace('Shopper\Framework\Http\Controllers')
            ->middleware(array_merge(config('shopper.middleware'), ['shopper.shop']))
            ->as('shopper.shop.')
            ->prefix(self::prefix() . '/shop')
            ->group(function () {
                Route::get('/initialize', 'ShopController@initialize')->name('initialize');
            });

        return $this;
    }
}
