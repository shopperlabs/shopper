<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | USPS Driver
    |--------------------------------------------------------------------------
    |
    | Here you may enable the USPS driver and point it at the USPS test
    | environment. A driver left disabled stays out of the carriers the
    | admin offers, even once its credentials are filled in.
    |
    */

    'enabled' => env('SHIPPING_USPS_ENABLED', false),

    'sandbox' => env('SHIPPING_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | USPS Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may set the credentials issued by the USPS developer portal.
    | Keep them in your .env file and never commit them.
    |
    */

    'client_id' => env('USPS_CLIENT_ID'),

    'client_secret' => env('USPS_CLIENT_SECRET'),

];
