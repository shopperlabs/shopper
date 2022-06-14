<?php

namespace Shopper\Framework;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use const LC_TIME;
use Maatwebsite\Sidebar\Middleware\ResolveSidebars;
use Shopper\Framework\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;
use Shopper\Framework\Contracts\TwoFactorAuthenticationProvider as TwoFactorAuthenticationProviderContract;
use Shopper\Framework\Events\BuildingSidebar;
use Shopper\Framework\Events\Handlers;
use Shopper\Framework\Http\Composers\GlobalComposer;
use Shopper\Framework\Http\Composers\SidebarCreator;
use Shopper\Framework\Http\Middleware;
use Shopper\Framework\Http\Responses\FailedTwoFactorLoginResponse;
use Shopper\Framework\Providers\ShopperServiceProvider;
use Shopper\Framework\Services\TwoFactor\TwoFactorAuthenticationProvider;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleMiddleware;

class FrameworkServiceProvider extends ServiceProvider
{
    protected array $middlewares = [
        'dashboard' => Middleware\Dashboard::class,
        'shopper.guest' => Middleware\RedirectIfAuthenticated::class,
        'shopper.setup' => Middleware\HasConfiguration::class,
        'role' => RoleMiddleware::class,
        'permission' => PermissionMiddleware::class,
    ];

    public function boot()
    {
        $this->bootDateFormatted();
        $this->registerMiddleware($this->app['router']);
        $this->registerShopSettingRoute();
        $this->registerViewsComposer();

        $this->app->register(ShopperServiceProvider::class);
    }

    public function register()
    {
        $this->app['events']->listen(BuildingSidebar::class, Handlers\RegisterDashboardSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, Handlers\RegisterShopSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, Handlers\RegisterOrderSidebar::class);

        $this->app->singleton('shopper', fn () => new Shopper());
        $this->app->singleton(TwoFactorAuthenticationProviderContract::class, TwoFactorAuthenticationProvider::class);
        $this->app->singleton(FailedTwoFactorLoginResponseContract::class, FailedTwoFactorLoginResponse::class);

        $this->app->bind(StatefulGuard::class, fn () => Auth::guard(config('shopper.auth.guard', null)));
    }

    public function bootDateFormatted()
    {
        // setLocale for php. Enables ->formatLocalized() with localized values for dates.
        setlocale(LC_TIME, config('shopper.system.locale'));

        // setLocale to use Carbon source locales. Enables diffForHumans() localized.
        Carbon::setLocale(config('app.locale'));
    }

    public function registerViewsComposer()
    {
        view()->composer('*', GlobalComposer::class);
        view()->creator('shopper::components.layouts.app.sidebar.secondary', SidebarCreator::class);
    }

    public function registerShopSettingRoute()
    {
        (new Shopper())->initializeRoute();
    }

    public function registerMiddleware(Router $router)
    {
        $router->middlewareGroup('shopper', array_merge([
            'web',
            Middleware\Authenticate::class,
            ResolveSidebars::class,
        ], config('shopper.routes.middleware', [])));

        foreach ($this->middlewares as $name => $middleware) {
            $router->aliasMiddleware($name, $middleware);
        }
    }

    public function provides(): array
    {
        return ['shopper'];
    }
}
