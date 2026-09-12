<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe Driver
    |--------------------------------------------------------------------------
    |
    | Here you may enable the Stripe driver. A driver left disabled stays out
    | of the payment methods the admin offers, even once its credentials are
    | filled in. Test mode is read from the secret key prefix.
    |
    */

    'enabled' => env('PAYMENT_STRIPE_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Stripe Credentials
    |--------------------------------------------------------------------------
    |
    | Configure your Stripe API keys. These should always be stored in your
    | .env file and never committed to version control.
    |
    */

    'secret_key' => env('STRIPE_SECRET_KEY'),

    'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),

    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Capture Method
    |--------------------------------------------------------------------------
    |
    | Determines how payments are captured. "manual" allows you to authorize
    | first and capture later (e.g. when order is shipped). "automatic"
    | captures the payment immediately upon confirmation.
    |
    | Supported: "manual", "automatic"
    |
    */

    'capture_method' => env('STRIPE_CAPTURE_METHOD', 'manual'),

];
