<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Measurement Units
    |--------------------------------------------------------------------------
    |
    | Define the default units for weight and dimensions used in shipping
    | calculations. Supported: 'metric' (kg, cm) or 'imperial' (lb, in)
    |
    */

    'units' => env('SHIPPING_UNITS', 'metric'),

    /*
    |--------------------------------------------------------------------------
    | Live Rates Cache
    |--------------------------------------------------------------------------
    |
    | Quotes returned by carrier APIs (UPS, FedEx, ...) are cached for this
    | many seconds, keyed by addresses and packages. Set to 0 to call the
    | carrier API on every rate request. Manual rates are never cached.
    |
    */

    'rates_cache_ttl' => env('SHIPPING_RATES_CACHE_TTL', 600),

    /*
    |--------------------------------------------------------------------------
    | Tracking Sync
    |--------------------------------------------------------------------------
    |
    | Here you may control the scheduled tracking refresh. Every thirty
    | minutes the undelivered shipments whose carrier driver supports
    | tracking are queued, and each job pulls the carrier timeline into
    | the shipment events. Carriers that push webhooks do not need it.
    |
    | High volumes are better kept off the default queue, where they would
    | sit in front of payment and order jobs. Name a queue below and run a
    | worker for it. Carrier tokens live in the default cache store, so run
    | a shared one when several nodes poll.
    |
    */

    'tracking' => [
        'sync' => env('SHIPPING_TRACKING_SYNC', true),
        'queue' => env('SHIPPING_TRACKING_QUEUE'),
        'backoff' => [60, 300, 900],
    ],

];
