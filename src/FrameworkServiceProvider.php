<?php

namespace Shopper\Framework;

use Carbon\Carbon;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Sidebar\Middleware\ResolveSidebars;
use Shopper\Framework\Events\BuildingSidebar;
use Shopper\Framework\Http\Middleware\Authenticate;
use Shopper\Framework\Events\Handlers\{
    RegisterDashboardSidebar,
    RegisterOrderSidebar,
    RegisterShopSidebar
};
use Shopper\Framework\Http\Composers\GlobalComposer;
use Shopper\Framework\Http\Composers\MenuCreator;
use Shopper\Framework\Http\Composers\SidebarCreator;
use Shopper\Framework\Http\Middleware\Dashboard;
use Shopper\Framework\Http\Middleware\RedirectIfAuthenticated;
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
        'dashboard'       => Dashboard::class,
        'role'            => RoleMiddleware::class,
        'permission'      => PermissionMiddleware::class,
        'shopper.guest'   => RedirectIfAuthenticated::class,
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMiddleware($this->app['router']);
        $this->registerShopSettingRoute();

        $this->app->register(ShopperServiceProvider::class);

        // setLocale for php. Enables ->formatLocalized() with localized values for dates.
        setlocale(LC_TIME, config('shopper.locale'));

        // setLocale to use Carbon source locales. Enables diffForHumans() localized.
        Carbon::setLocale(config('app.locale'));

        // Global Composer
        // This class binds the $logged_in_user variable to every view.
        view()->composer('*', GlobalComposer::class);

        // Backend Menu
        view()->creator('shopper::partials.' . config('shopper.theme') . '.aside._secondary', SidebarCreator::class);
        view()->composer('shopper::partials.' . config('shopper.theme') . '.aside._primary', MenuCreator::class);
    }

    /**
     * Register the Shop routes.
     *
     * @return void
     */
    public function registerShopSettingRoute()
    {
        (new Shopper())->initializeRoute();
    }

    /**
     * Register the middleware.
     *
     * @param  Router  $router
     * @return void
     */
    public function registerMiddleware(Router $router)
    {
        $router->middlewareGroup('shopper', array_merge([
            'web',
            Authenticate::class,
            ResolveSidebars::class,
        ], config('shopper.routes.middleware', [])));

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
        // Register Default Dashboard Menu
        $this->app['events']->listen(BuildingSidebar::class, RegisterDashboardSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, RegisterShopSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, RegisterOrderSidebar::class);

        // Register the service the package provides.
        $this->app->singleton('shopper', function () {
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
