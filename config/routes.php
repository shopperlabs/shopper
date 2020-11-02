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

    'prefix' => env('SHOPPER_DASHBOARD_PREFIX', 'shopper'),

    /*
    |--------------------------------------------------------------------------
    | Shopper Routes Middleware
    |--------------------------------------------------------------------------
    |
    | Here you may specify which middleware Shopper will assign to the routes
    | that it registers with the application. If necessary, you may change
    | these middleware but typically this provided default is preferred.
    |
    */

    'middleware' => [],

    /*
    |--------------------------------------------------------------------------
    | Shopper Custom backend route file
    |--------------------------------------------------------------------------
    |
    | This value sets the file to load for Shopper admin custom routes. This
    | depend of your application.
    | Eg.: base_path('routes/shopper.php')
    |
    */

    'custom_file' => null,
];
