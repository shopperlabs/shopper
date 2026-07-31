<?php

declare(strict_types=1);

namespace Shopper\Http;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Zone;
use Shopper\Http\Channel\DefaultChannelResolver;
use Shopper\Http\Contracts\ChannelResolver;
use Shopper\Http\Contracts\ZoneResolver;
use Shopper\Http\Enum\RateLimit;
use Shopper\Http\Exceptions\JsonApiErrorRenderer;
use Shopper\Http\Middleware\EnsureJsonApiResponse;
use Shopper\Http\Middleware\NegotiateLocale;
use Shopper\Http\Middleware\ResolveChannel;
use Shopper\Http\Middleware\ResolveCustomer;
use Shopper\Http\Middleware\ResolveZone;
use Shopper\Http\Zone\DefaultZoneResolver;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Throwable;

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

        $this->app->bind(
            ChannelResolver::class,
            fn ($app): ChannelResolver => $app->make((string) config('shopper.http.channel.resolver', DefaultChannelResolver::class)),
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
        $this->registerExceptionRenderer();
        $this->registerZoneCacheInvalidation();
        $this->registerChannelCacheInvalidation();
    }

    private function registerChannelCacheInvalidation(): void
    {
        $forget = static function (Channel $channel): void {
            Cache::forget(DefaultChannelResolver::cacheKey((string) $channel->slug));

            if ($channel->wasChanged('slug')) {
                Cache::forget(DefaultChannelResolver::cacheKey((string) $channel->getOriginal('slug')));
            }
        };

        Channel::saved($forget);
        Channel::deleted($forget);
    }

    private function registerZoneCacheInvalidation(): void
    {
        $forget = static function (Zone $zone): void {
            Cache::forget(DefaultZoneResolver::cacheKey((string) $zone->code));

            if ($zone->wasChanged('code')) {
                Cache::forget(DefaultZoneResolver::cacheKey((string) $zone->getOriginal('code')));
            }
        };

        Zone::saved($forget);
        Zone::deleted($forget);
    }

    private function registerExceptionRenderer(): void
    {
        $this->callAfterResolving(ExceptionHandler::class, function (ExceptionHandler $handler): void {
            $renderer = new JsonApiErrorRenderer;

            $handler->renderable(
                fn (Throwable $exception, Request $request): ?JsonResponse => $renderer->render($exception, $request),
            );
        });
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
            NegotiateLocale::class,
            ResolveZone::class,
            ResolveChannel::class,
        ], (array) config('shopper.http.middleware.store', [])));

        $router->middlewareGroup('shopper:store-auth', array_merge([
            'throttle:'.RateLimit::Default->value,
            EnsureJsonApiResponse::class,
            NegotiateLocale::class,
            ResolveZone::class,
            ResolveChannel::class,
            'auth:sanctum',
            ResolveCustomer::class,
        ], (array) config('shopper.http.middleware.authenticated', [])));

        $router->middlewareGroup('shopper:store-webhook', array_merge([
            'throttle:'.RateLimit::Webhook->value,
        ], (array) config('shopper.http.middleware.webhook', [])));
    }
}
