<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | UPS Driver
    |--------------------------------------------------------------------------
    |
    | Here you may enable the UPS driver and point it at the UPS test
    | environment. A driver left disabled stays out of the carriers the
    | admin offers, even once its credentials are filled in.
    |
    */

    'enabled' => env('SHIPPING_UPS_ENABLED', false),

    'sandbox' => env('SHIPPING_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | UPS Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may set the credentials issued by the UPS developer portal.
    | Keep them in your .env file and never commit them. The account number
    | is the shipper number your negotiated rates are attached to.
    |
    */

    'client_id' => env('UPS_CLIENT_ID'),

    'client_secret' => env('UPS_CLIENT_SECRET'),

    'user_id' => env('UPS_USER_ID'),

    'account_number' => env('UPS_ACCOUNT_NUMBER'),

];
