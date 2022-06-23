<?php

namespace Shopper\Framework\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{RateLimiter, Route};
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
        $this->configureRateLimiting();

        $this->mapApiRoutes();

        $this->mapAuthRoutes();

        $this->mapBackendRoutes();

        $this->mapCustomBackendRoute();
    }

    public function mapApiRoutes()
    {
        Route::namespace($this->namespace . '\Api')
            ->middleware('api')
            ->as('shopper.api.')
            ->prefix(Shopper::prefix() . '/api')
            ->group(realpath(SHOPPER_PATH . '/routes/api.php'));
    }

    protected function mapAuthRoutes()
    {
        Route::namespace($this->namespace . '\Auth')
            ->middleware('web')
            ->as('shopper.')
            ->prefix(Shopper::prefix())
            ->group(realpath(SHOPPER_PATH . '/routes/auth.php'));
    }

    protected function mapBackendRoutes()
    {
        Route::middleware(['shopper', 'dashboard'])
            ->prefix(Shopper::prefix())
            ->as('shopper.')
            ->namespace($this->namespace)
            ->group(realpath(SHOPPER_PATH . '/routes/backend.php'));
    }

    public function mapCustomBackendRoute()
    {
        if (config('shopper.routes.custom_file')) {
            Route::middleware(['shopper', 'dashboard'])
                ->prefix(Shopper::prefix())
                ->namespace(config('shopper.system.controllers.namespace'))
                ->group(config('shopper.routes.custom_file'));
        }
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('shopper.api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
