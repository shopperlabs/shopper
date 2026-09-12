<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | FedEx Credentials
    |--------------------------------------------------------------------------
    |
    | Configure your FedEx API credentials. These should always be stored in
    | your .env file and never committed to version control. Rates are
    | quoted against the account number given below.
    |
    */

    'enabled' => env('SHIPPING_FEDEX_ENABLED', false),

    'sandbox' => env('SHIPPING_SANDBOX', false),

    'credentials' => [
        'client_id' => env('FEDEX_CLIENT_ID'),
        'client_secret' => env('FEDEX_CLIENT_SECRET'),
        'account_number' => env('FEDEX_ACCOUNT_NUMBER'),
    ],

];
