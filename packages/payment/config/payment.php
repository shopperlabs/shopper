<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Drivers
    |--------------------------------------------------------------------------
    |
    | Configure payment provider drivers. Each driver connects to a payment
    | gateway API (Stripe, PayPal, etc.) for processing payments,
    | captures, and refunds.
    |
    | Credentials should be stored in your .env file, never in the database.
    |
    */

    'drivers' => [

        'stripe' => [
            'enabled' => env('PAYMENT_STRIPE_ENABLED', false),
            'sandbox' => env('PAYMENT_SANDBOX', false),
            'credentials' => [
                'secret_key' => env('STRIPE_SECRET_KEY'),
                'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
                'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            ],
        ],

        'paypal' => [
            'enabled' => env('PAYMENT_PAYPAL_ENABLED', false),
            'sandbox' => env('PAYMENT_SANDBOX', false),
            'credentials' => [
                'client_id' => env('PAYPAL_CLIENT_ID'),
                'client_secret' => env('PAYPAL_CLIENT_SECRET'),
                'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Reconciliation
    |--------------------------------------------------------------------------
    |
    | Here you may control how an order catches up with the provider events
    | that arrived before it existed. When a cart is completed the stored
    | events are replayed and a provider check is queued for a payment still
    | pending. Every fifteen minutes the scheduled command replays what is
    | still unprocessed and queues a check for every pending payment. The
    | checks run on the queue configured here, and the event ledger is
    | pruned daily past the retention below.
    |
    */

    'reconciliation' => [
        'pull_on_completion' => env('PAYMENT_PULL_ON_COMPLETION', true),
        'schedule' => env('PAYMENT_RECONCILE_SCHEDULE', true),
        'queue' => env('PAYMENT_RECONCILE_QUEUE'),
        'backoff' => [60, 300, 900],
        'prune_after_days' => env('PAYMENT_WEBHOOK_EVENTS_PRUNE_AFTER_DAYS', 90),
    ],

];
