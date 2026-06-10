<?php

declare(strict_types=1);

namespace Shopper\Http;

use Closure;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;

final class ShopperApiRouter
{
    public function store(Closure $routes): RouteRegistrar
    {
        return Route::prefix($this->prefix())
            ->middleware('shopper:store')
            ->group($routes);
    }

    public function authenticated(Closure $routes): RouteRegistrar
    {
        return Route::prefix($this->prefix())
            ->middleware('shopper:store-auth')
            ->group($routes);
    }

    public function prefix(): string
    {
        return (string) config('shopper.http.prefix', 'store');
    }
}
