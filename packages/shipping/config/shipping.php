<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Shipping Drivers
    |--------------------------------------------------------------------------
    |
    | Configure shipping provider drivers. Each driver connects to a shipping
    | carrier API (UPS, FedEx, DHL, etc.) for real-time rate calculation,
    | shipment creation, and tracking.
    |
    | Credentials should be stored in your .env file, never in the database.
    |
    */

    'drivers' => [

        'ups' => [
            'enabled' => env('SHIPPING_UPS_ENABLED', false),
            'sandbox' => env('SHIPPING_SANDBOX', false),
            'credentials' => [
                'client_id' => env('UPS_CLIENT_ID'),
                'client_secret' => env('UPS_CLIENT_SECRET'),
                'user_id' => env('UPS_USER_ID'),
                'account_number' => env('UPS_ACCOUNT_NUMBER'),
            ],
        ],

        'fedex' => [
            'enabled' => env('SHIPPING_FEDEX_ENABLED', false),
            'sandbox' => env('SHIPPING_SANDBOX', false),
            'credentials' => [
                'client_id' => env('FEDEX_CLIENT_ID'),
                'client_secret' => env('FEDEX_CLIENT_SECRET'),
                'account_number' => env('FEDEX_ACCOUNT_NUMBER'),
            ],
        ],

        'usps' => [
            'enabled' => env('SHIPPING_USPS_ENABLED', false),
            'sandbox' => env('SHIPPING_SANDBOX', false),
            'credentials' => [
                'client_id' => env('USPS_CLIENT_ID'),
                'client_secret' => env('USPS_CLIENT_SECRET'),
            ],
        ],

        'canada_post' => [
            'enabled' => env('SHIPPING_CANADA_POST_ENABLED', false),
            'sandbox' => env('SHIPPING_SANDBOX', false),
            'credentials' => [
                'customer_number' => env('CANADA_POST_CUSTOMER_NUMBER'),
                'username' => env('CANADA_POST_USERNAME'),
                'password' => env('CANADA_POST_PASSWORD'),
            ],
        ],

        'purolator' => [
            'enabled' => env('SHIPPING_PUROLATOR_ENABLED', false),
            'sandbox' => env('SHIPPING_SANDBOX', false),
            'credentials' => [
                'key' => env('PUROLATOR_KEY'),
                'password' => env('PUROLATOR_PASSWORD'),
                'billing_account' => env('PUROLATOR_BILLING_ACCOUNT'),
                'registered_account' => env('PUROLATOR_REGISTERED_ACCOUNT'),
                'user_token' => env('PUROLATOR_USER_TOKEN'),
            ],
        ],

    ],

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

];
