<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | UPS Credentials
    |--------------------------------------------------------------------------
    |
    | Configure your UPS API credentials. These should always be stored in
    | your .env file and never committed to version control. The account
    | number is the shipper number your rates are negotiated against.
    |
    */

    'enabled' => env('SHIPPING_UPS_ENABLED', false),

    'sandbox' => env('SHIPPING_SANDBOX', false),

    'credentials' => [
        'client_id' => env('UPS_CLIENT_ID'),
        'client_secret' => env('UPS_CLIENT_SECRET'),
        'user_id' => env('UPS_USER_ID'),
        'account_number' => env('UPS_ACCOUNT_NUMBER'),
    ],

];
