<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Standard HTTP content negotiation for the store API: the client sends
 * Accept-Language and everything locale-aware downstream (translated country
 * names, translatable addons, validation messages) answers in that language.
 *
 * The supported locales are the ones configured for the shop
 * (shopper.admin.locales), a single source of truth with the admin panel.
 * The header is negotiated against that list, never trusted raw: the locale
 * ends up in file paths and cache keys. No header means the application
 * default applies. Not to be confused with the admin SetLocale middleware,
 * which reads the admin user's session.
 */
final readonly class NegotiateLocale
{
    public function __construct(private Application $app) {}

    public function handle(Request $request, Closure $next): Response
    {
        $locales = array_keys((array) config('shopper.admin.locales', []));

        if ($locales !== [] && $request->headers->has('Accept-Language')) {
            $this->app->setLocale($request->getPreferredLanguage($locales));
        }

        return $next($request);
    }
}
