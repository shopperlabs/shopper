<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | FedEx Driver
    |--------------------------------------------------------------------------
    |
    | Here you may enable the FedEx driver and point it at the FedEx test
    | environment. A driver left disabled stays out of the carriers the
    | admin offers, even once its credentials are filled in.
    |
    */

    'enabled' => env('SHIPPING_FEDEX_ENABLED', false),

    'sandbox' => env('SHIPPING_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | FedEx Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may set the credentials issued by the FedEx developer portal.
    | Keep them in your .env file and never commit them. Rates are quoted
    | against the account number given below.
    |
    */

    'client_id' => env('FEDEX_CLIENT_ID'),

    'client_secret' => env('FEDEX_CLIENT_SECRET'),

    'account_number' => env('FEDEX_ACCOUNT_NUMBER'),

];
