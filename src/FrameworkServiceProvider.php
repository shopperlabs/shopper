<?php

namespace Shopper\Framework;

use Carbon\Carbon;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Shopper\Framework\Events\BuildingSidebar;
use Shopper\Framework\Events\Handlers\RegisterBannerSidebar;
use Shopper\Framework\Events\Handlers\RegisterDashboardSidebar;
use Shopper\Framework\Events\Handlers\RegisterOrderSidebar;
use Shopper\Framework\Events\Handlers\RegisterShopSidebar;
use Shopper\Framework\Http\Composers\GlobalComposer;
use Shopper\Framework\Http\Composers\MenuCreator;
use Shopper\Framework\Http\Composers\SidebarCreator;
use Shopper\Framework\Http\Middleware\Dashboard;
use Shopper\Framework\Http\Middleware\RedirectIfAuthenticated;
use Shopper\Framework\Http\Middleware\RedirectIfShop;
use Shopper\Framework\Providers\ShopperServiceProvider;
use Shopper\Framework\Services\Gravatar;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleMiddleware;

class FrameworkServiceProvider extends ServiceProvider
{
    /**
     * The middleware base class name.
     *
     * @var array
     */
    protected $middlewares = [
        'role'            => RoleMiddleware::class,
        'permission'      => PermissionMiddleware::class,
        'shopper.guest'   => RedirectIfAuthenticated::class,
        'dashboard'       => Dashboard::class,
        'shopper.shop'    => RedirectIfShop::class
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerShopRoute();

        $this->app->register(ShopperServiceProvider::class);

        Route::middlewareGroup('shopper', config('shopper.middleware', []));
        $this->registerMiddleware($this->app['router']);

        // setLocale for php. Enables ->formatLocalized() with localized values for dates
        setlocale(LC_TIME, config('app.locale_php'));

        // setLocale to use Carbon source locales. Enables diffForHumans() localized
        Carbon::setLocale(config('app.locale'));

        // Global Composer
        // This class binds the $logged_in_user variable to every view
        view()->composer('*', GlobalComposer::class);

        // Backend Menu
        view()->creator('shopper::partials.'. config('shopper.theme') .'.aside._secondary', SidebarCreator::class);
        view()->composer('shopper::partials.'. config('shopper.theme') .'.aside._primary', MenuCreator::class);
    }

    /**
     * Register the Shop routes.
     *
     * @return void
     */
    public function registerShopRoute()
    {
        (new Shopper())->initializeRoute();
    }

    /**
     * Register the middleware.
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware(Router $router)
    {
        foreach ($this->middlewares as $name => $middleware) {
            $router->aliasMiddleware($name, $middleware);
        }
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

        $this->mergeConfigFrom(SHOPPER_PATH.'/config/shopper.php', 'shopper');

        // Register Default Dashboard Menu
        $this->app['events']->listen(BuildingSidebar::class, RegisterDashboardSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, RegisterShopSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, RegisterOrderSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, RegisterBannerSidebar::class);

        // Register the service the package provides.
        $this->app->singleton('shopper', function ($app) {
            return new Shopper;
        });

        $this->app->singleton('gravatar', function () {
            return new Gravatar;
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
