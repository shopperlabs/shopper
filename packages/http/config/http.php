<?php

declare(strict_types=1);

use Shopper\Http\Enum\RateLimit;

return [
    /*
    |--------------------------------------------------------------------------
    | API prefix
    |--------------------------------------------------------------------------
    |
    | The prefix applied to every route registered through the shopper:store
    | and shopper:store-auth groups. Defaults to "store", aligned with Medusa.
    | The /admin prefix is reserved for a future admin API.
    |
    */
    'prefix' => env('SHOPPER_API_PREFIX', 'store'),

    /*
    |--------------------------------------------------------------------------
    | Rate limiters
    |--------------------------------------------------------------------------
    |
    | Max attempts per minute for each named limiter. Keyed by the RateLimit
    | enum so the names stay searchable and type-safe. Tune these per traffic
    | profile; the auth and webhook surfaces are keyed by IP, the others by
    | the authenticated customer when available.
    |
    */
    'rate_limiters' => [
        RateLimit::Default->value => 120,
        RateLimit::Auth->value => 20,
        RateLimit::Checkout->value => 30,
        RateLimit::Webhook->value => 120,
    ],

    /*
    |--------------------------------------------------------------------------
    | Zone resolution
    |--------------------------------------------------------------------------
    |
    | The zone is a soft pricing context, never a hard gate. Zones are matched
    | by their unique "code". The resolver is swappable: provide your own
    | implementation to resolve a zone from a country, geo-ip, or any custom
    | strategy. When nothing resolves, the request proceeds with a null zone
    | and the relevant endpoints decide.
    |
    */
    'zone' => [
        'resolver' => Shopper\Http\Zone\DefaultZoneResolver::class,
        'header' => 'X-Shopper-Zone',
        'default_code' => env('SHOPPER_API_DEFAULT_ZONE'),
        'cache_ttl' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Channel resolution
    |--------------------------------------------------------------------------
    |
    | The sales channel is a soft catalog context, never a hard gate. Channels
    | are matched by their unique "slug". The resolver is swappable: provide
    | your own implementation to resolve a channel from a domain, an API key,
    | or any custom strategy. When nothing resolves, the request proceeds with
    | a null channel and the catalog stays unfiltered.
    |
    */
    'channel' => [
        'resolver' => Shopper\Http\Channel\DefaultChannelResolver::class,
        'header' => 'X-Shopper-Channel',
        'default_slug' => env('SHOPPER_API_DEFAULT_CHANNEL'),
        'cache_ttl' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Extra middleware
    |--------------------------------------------------------------------------
    |
    | Appended to the store groups. "store" applies to every public route,
    | "authenticated" applies in addition to the authenticated group.
    |
    */
    'middleware' => [
        'store' => [],
        'authenticated' => [],
        'webhook' => [],
    ],
];
