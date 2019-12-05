<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
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
     * Define your route model bindings, pattern filters, etc.
     *
     * @internal param Router $router
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapAuthRoutes();
        $this->mapBackendRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Define the "auth" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAuthRoutes()
    {
        Route::namespace($this->namespace . '\Auth')
            ->middleware(['web'])
            ->as('shopper.')
            ->prefix(Shopper::prefix())
            ->group(realpath(SHOPPER_PATH . '/routes/auth.php'));
    }

    /**
     * Define the "backend" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapBackendRoutes()
    {
        Route::middleware(array_merge(config('shopper.middleware'), ['dashboard']))
            ->prefix(Shopper::prefix())
            ->as('shopper.')
            ->namespace($this->namespace)
            ->group(realpath(SHOPPER_PATH . '/routes/backend.php'));
    }

    /**
     * Define the "api" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware(config('shopper.middleware_api'))
            ->prefix('shopper-api')
            ->as('shopper.api.')
            ->namespace($this->namespace . '\Api')
            ->group(realpath(SHOPPER_PATH . '/routes/api.php'));
    }
}
