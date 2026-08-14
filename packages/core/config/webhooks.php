<?php

declare(strict_types=1);

use Shopper\Core\Enum\WebhookEventType;
use Shopper\Core\Events\Customers\CustomerRegistered;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Events\Orders\OrderCompleted;
use Shopper\Core\Events\Orders\OrderCreated;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Events\Orders\OrderShipped;
use Shopper\Core\Events\Products\ProductCreated;
use Shopper\Core\Events\Products\ProductDeleted;
use Shopper\Core\Events\Products\ProductUpdated;

return [
    /*
    |--------------------------------------------------------------------------
    | Webhook Events
    |--------------------------------------------------------------------------
    |
    | Here you may map internal event classes to the public names webhook
    | subscriptions listen to. Packages and addons register their own events
    | through the Webhooks facade in their service provider. The public name
    | is the wire contract and must stay stable.
    |
    */

    'events' => [
        OrderCreated::class => WebhookEventType::OrderCreated->value,
        OrderPaid::class => WebhookEventType::OrderPaid->value,
        OrderCancelled::class => WebhookEventType::OrderCancelled->value,
        OrderShipped::class => WebhookEventType::OrderShipped->value,
        OrderCompleted::class => WebhookEventType::OrderCompleted->value,
        ProductCreated::class => WebhookEventType::ProductCreated->value,
        ProductUpdated::class => WebhookEventType::ProductUpdated->value,
        ProductDeleted::class => WebhookEventType::ProductDeleted->value,
        CustomerRegistered::class => WebhookEventType::CustomerRegistered->value,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | Here you may configure the database connection used to store webhook
    | subscriptions, events and deliveries. High-volume stores can point
    | this to a dedicated database so webhook traffic never competes with
    | commerce data. When null, the default connection is used.
    |
    */

    'database' => [
        'connection' => env('SHOPPER_WEBHOOKS_DB_CONNECTION'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Endpoint Restrictions
    |--------------------------------------------------------------------------
    |
    | Here you may restrict where webhooks may be sent. An empty allowed_hosts
    | list permits any public host; add hosts to lock deliveries down to known
    | integrations, so a compromised admin cannot turn the store into a data
    | exfiltration or amplification channel. A subscription cap bounds the
    | fan-out an attacker could create.
    |
    */

    'allowed_hosts' => array_filter(explode(',', (string) env('SHOPPER_WEBHOOKS_ALLOWED_HOSTS', ''))),

    'max_subscriptions' => (int) env('SHOPPER_WEBHOOKS_MAX_SUBSCRIPTIONS', 50),

    /*
    |--------------------------------------------------------------------------
    | Delivery
    |--------------------------------------------------------------------------
    |
    | Deliveries run on their own queue so webhook traffic never starves the
    | order and payment jobs. The backoff schedule drives the retry delays,
    | and a subscription is disabled after this many consecutive failures.
    | Signatures older than the tolerance window are rejected by receivers.
    |
    */

    'queue' => env('SHOPPER_WEBHOOKS_QUEUE', 'webhooks'),

    'backoff' => [10, 30, 60, 300, 1800, 3600, 7200, 14400, 28800, 43200],

    'disable_after_failures' => 15,

    'timeout' => 10,

    'signature_tolerance' => 300,

    /*
    |--------------------------------------------------------------------------
    | Retention
    |--------------------------------------------------------------------------
    |
    | Succeeded deliveries are pruned quickly, failed ones are kept longer
    | for diagnostics. Used by the Prunable models with `model:prune`.
    |
    */

    'prune' => [
        'succeeded_after_days' => 7,
        'failed_after_days' => 90,
    ],
];
