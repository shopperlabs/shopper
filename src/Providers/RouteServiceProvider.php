<?php

declare(strict_types=1);

namespace Shopper\Framework\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Shopper\Framework\Shopper;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'Shopper\Framework\Http\Controllers';

    public function map(): void
    {
        $this->configureRateLimiting();

        $this->mapApiRoutes();

        $this->mapAuthRoutes();

        $this->mapBackendRoutes();

        $this->mapCustomBackendRoute();
    }

    public function mapApiRoutes(): void
    {
        Route::namespace($this->namespace . '\Api')
            ->middleware('api')
            ->as('shopper.api.')
            ->prefix(Shopper::prefix() . '/api')
            ->group(realpath(SHOPPER_PATH . '/routes/api.php'));
    }

    protected function mapAuthRoutes(): void
    {
        Route::namespace($this->namespace . '\Auth')
            ->middleware('web')
            ->as('shopper.')
            ->prefix(Shopper::prefix())
            ->group(realpath(SHOPPER_PATH . '/routes/auth.php'));
    }

    protected function mapBackendRoutes(): void
    {
        Route::middleware(['shopper', 'dashboard'])
            ->prefix(Shopper::prefix())
            ->as('shopper.')
            ->namespace($this->namespace)
            ->group(realpath(SHOPPER_PATH . '/routes/backend.php'));
    }

    public function mapCustomBackendRoute(): void
    {
        if (config('shopper.routes.custom_file')) {
            Route::middleware(['shopper', 'dashboard'])
                ->prefix(Shopper::prefix())
                ->namespace(config('shopper.system.controllers.namespace'))
                ->group(config('shopper.routes.custom_file'));
        }
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('shopper.api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
