<?php

declare(strict_types=1);

namespace Shopper\Framework\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Shopper\Framework\Shopper;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'Shopper\Framework\Http\Controllers';

    public function map(): void
    {
        $this->mapAuthRoutes();

        $this->mapBackendRoutes();

        $this->mapCustomBackendRoute();
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
}
