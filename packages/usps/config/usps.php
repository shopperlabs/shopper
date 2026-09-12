<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | USPS Credentials
    |--------------------------------------------------------------------------
    |
    | Configure your USPS API credentials. These should always be stored in
    | your .env file and never committed to version control.
    |
    */

    'enabled' => env('SHIPPING_USPS_ENABLED', false),

    'sandbox' => env('SHIPPING_SANDBOX', false),

    'credentials' => [
        'client_id' => env('USPS_CLIENT_ID'),
        'client_secret' => env('USPS_CLIENT_SECRET'),
    ],

];
