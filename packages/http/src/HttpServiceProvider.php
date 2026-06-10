<?php

declare(strict_types=1);

namespace Shopper\Http;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\RateLimiter;
use Shopper\Http\Contracts\ZoneResolver;
use Shopper\Http\Enum\RateLimit;
use Shopper\Http\Middleware\EnsureJsonApiResponse;
use Shopper\Http\Middleware\ResolveCustomer;
use Shopper\Http\Middleware\ResolveZone;
use Shopper\Http\Zone\DefaultZoneResolver;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class HttpServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('shopper-http');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/http.php', 'shopper.http');

        $this->app->bind(
            ZoneResolver::class,
            fn ($app): ZoneResolver => $app->make((string) config('shopper.http.zone.resolver', DefaultZoneResolver::class)),
        );

        $this->app->singleton(ShopperApiRouter::class);
    }

    public function packageBooted(): void
    {
        $this->publishes(
            [__DIR__.'/../config/http.php' => config_path('shopper/http.php')],
            'shopper-config',
        );

        $this->registerRateLimiters();
        $this->registerMiddleware();
    }

    private function registerRateLimiters(): void
    {
        RateLimiter::for(
            RateLimit::Default,
            fn (Request $request): Limit => Limit::perMinute(RateLimit::Default->maxAttempts())->by($request->user()?->getAuthIdentifier() ?? $request->ip()),
        );

        RateLimiter::for(
            RateLimit::Auth,
            fn (Request $request): Limit => Limit::perMinute(RateLimit::Auth->maxAttempts())->by($request->ip()),
        );

        RateLimiter::for(
            RateLimit::Checkout,
            fn (Request $request): Limit => Limit::perMinute(RateLimit::Checkout->maxAttempts())->by($request->user()?->getAuthIdentifier() ?? $request->ip()),
        );

        RateLimiter::for(
            RateLimit::Webhook,
            fn (Request $request): Limit => Limit::perMinute(RateLimit::Webhook->maxAttempts())->by($request->ip()),
        );
    }

    private function registerMiddleware(): void
    {
        $router = $this->app->make(Router::class);

        $router->middlewareGroup('shopper:store', array_merge([
            'throttle:'.RateLimit::Default->value,
            EnsureJsonApiResponse::class,
            ResolveZone::class,
        ], (array) config('shopper.http.middleware.store', [])));

        $router->middlewareGroup('shopper:store-auth', array_merge([
            'throttle:'.RateLimit::Default->value,
            EnsureJsonApiResponse::class,
            ResolveZone::class,
            'auth:sanctum',
            ResolveCustomer::class,
        ], (array) config('shopper.http.middleware.authenticated', [])));
    }
}
