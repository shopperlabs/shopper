<?php

namespace Shopper\Framework;

use const LC_TIME;
use Carbon\Carbon;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\StatefulGuard;
use Maatwebsite\Sidebar\Middleware\ResolveSidebars;
use Shopper\Framework\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;
use Shopper\Framework\Events\BuildingSidebar;
use Shopper\Framework\Events\Handlers\RegisterDashboardSidebar;
use Shopper\Framework\Events\Handlers\RegisterOrderSidebar;
use Shopper\Framework\Events\Handlers\RegisterShopSidebar;
use Shopper\Framework\Exceptions\ShopperExceptionHandler;
use Shopper\Framework\Http\Middleware\Dashboard;
use Shopper\Framework\Http\Composers\MenuCreator;
use Shopper\Framework\Http\Composers\GlobalComposer;
use Shopper\Framework\Http\Composers\SidebarCreator;
use Shopper\Framework\Http\Middleware\Authenticate;
use Shopper\Framework\Http\Middleware\HasConfiguration;
use Shopper\Framework\Http\Middleware\RedirectIfAuthenticated;
use Shopper\Framework\Http\Responses\FailedTwoFactorLoginResponse;
use Shopper\Framework\Providers\ShopperServiceProvider;
use Shopper\Framework\Services\Gravatar;
use Shopper\Framework\Services\TwoFactor\TwoFactorAuthenticationProvider;
use Shopper\Framework\Contracts\TwoFactorAuthenticationProvider as TwoFactorAuthenticationProviderContract;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;

class FrameworkServiceProvider extends ServiceProvider
{
    /**
     * The middleware base class name.
     */
    protected array $middlewares = [
        'dashboard' => Dashboard::class,
        'role' => RoleMiddleware::class,
        'permission' => PermissionMiddleware::class,
        'shopper.guest' => RedirectIfAuthenticated::class,
        'shopper.setup' => HasConfiguration::class,
    ];

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->registerMiddleware($this->app['router']);
        $this->registerShopSettingRoute();

        $this->app->register(ShopperServiceProvider::class);

        // setLocale for php. Enables ->formatLocalized() with localized values for dates.
        setlocale(LC_TIME, config('shopper.system.locale'));

        // setLocale to use Carbon source locales. Enables diffForHumans() localized.
        Carbon::setLocale(config('app.locale'));

        // Global Composer
        // This class binds the $logged_in_user variable to every view.
        view()->composer('*', GlobalComposer::class);

        // Backend Menu
        view()->creator('shopper::partials.default.aside._secondary', SidebarCreator::class);
        view()->composer('shopper::partials.default.aside._primary', MenuCreator::class);
    }

    /**
     * Register the Shop routes.
     */
    public function registerShopSettingRoute()
    {
        (new Shopper())->initializeRoute();
    }

    /**
     * Register the middleware.
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
     */
    public function register()
    {
        // Register Default Dashboard Menu
        $this->app['events']->listen(BuildingSidebar::class, RegisterDashboardSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, RegisterShopSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, RegisterOrderSidebar::class);

        // Register the service the package provides.
        $this->app->singleton('shopper', fn () => new Shopper());
        $this->app->singleton('gravatar', fn () => new Gravatar());
        $this->app->singleton(TwoFactorAuthenticationProviderContract::class, TwoFactorAuthenticationProvider::class);
        $this->app->singleton(FailedTwoFactorLoginResponseContract::class, FailedTwoFactorLoginResponse::class);

        $this->app->bind(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            ShopperExceptionHandler::class
        );

        $this->app->bind(StatefulGuard::class, fn () => Auth::guard(config('shopper.auth.guard', null)));
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['shopper'];
    }
}
