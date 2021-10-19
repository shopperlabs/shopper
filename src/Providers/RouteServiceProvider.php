<?php

namespace Shopper\Framework\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Shopper\Framework\Shopper;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Shopper\Framework\Http\Controllers';

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        $this->mapAuthRoutes();

        $this->mapBackendRoutes();

        $this->mapCustomBackendRoute();
    }

    /**
     * Define the "custom backend" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     */
    public function mapCustomBackendRoute()
    {
        if (config('shopper.routes.custom_file')) {
            Route::middleware(['shopper', 'dashboard'])
                ->prefix(Shopper::prefix())
                ->namespace(config('shopper.system.controllers.namespace'))
                ->group(config('shopper.routes.custom_file'));
        }
    }

    /**
     * Define the "auth" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapAuthRoutes()
    {
        Route::namespace($this->namespace . '\Auth')
            ->middleware('web')
            ->as('shopper.')
            ->prefix(Shopper::prefix())
            ->group(realpath(SHOPPER_PATH . '/routes/auth.php'));
    }

    /**
     * Define the "backend" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapBackendRoutes()
    {
        Route::middleware(['shopper', 'dashboard'])
            ->prefix(Shopper::prefix())
            ->as('shopper.')
            ->namespace($this->namespace)
            ->group(realpath(SHOPPER_PATH . '/routes/backend.php'));
    }
}
