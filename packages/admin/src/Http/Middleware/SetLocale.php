<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        $available = array_keys(config('shopper.admin.locales', []));
        $locale = session('shopper_locale', config('app.locale'));

        if (in_array($locale, $available, strict: true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
