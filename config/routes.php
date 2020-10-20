<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | This prefix method can be used for the prefix of each
    | route in the administration panel. For example, you can change to 'admin'.
    |
    */

    'prefix' => env('DASHBOARD_PREFIX', 'shopper'),

    /*
    |--------------------------------------------------------------------------
    | Middleware Admin Panel
    |--------------------------------------------------------------------------
    |
    | Provide a convenient mechanism for filtering HTTP requests entering
    | your shopper application. Add your custom middleware here.
    |
    */

    'middleware' => [],

    /*
    |--------------------------------------------------------------------------
    | Shopper Custom backend route file
    |--------------------------------------------------------------------------
    |
    | This value sets the file to load for Shopper admin custom routes. This
    | depend of your application. For example for "shopper.php" Shopper will load
    | the file into "routes/shopper.php"
    |
    */

    'custom_file' => null,
];
