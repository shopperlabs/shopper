<?php

declare(strict_types=1);

return [

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
