<?php

namespace Shopper\Framework;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Shopper\Framework\Http\Middleware\RedirectIfAuthenticated;
use Shopper\Framework\Providers\ShopperServiceProvider;

class FrameworkServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(ShopperServiceProvider::class);

        Route::middlewareGroup('shopper', config('shopper.middleware', []));
        Route::aliasMiddleware('shopper.guest', RedirectIfAuthenticated::class);

        // setLocale for php. Enables ->formatLocalized() with localized values for dates
        setlocale(LC_TIME, config('app.locale_php'));

        // setLocale to use Carbon source locales. Enables diffForHumans() localized
        Carbon::setLocale(config('app.locale'));
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        if (! defined('SHOPPER_PATH')) {
            define('SHOPPER_PATH', realpath(__DIR__.'/../'));
        }

        $this->mergeConfigFrom(__DIR__.'/../config/shopper.php', 'shopper');

        // Register the service the package provides.
        $this->app->singleton('shopper', function ($app) {
            return new Shopper();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['shopper'];
    }
}
