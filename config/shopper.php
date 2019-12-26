<?php

use Maatwebsite\Sidebar\Middleware\ResolveSidebars;
use Shopper\Framework\Http\Middleware\Authenticate;

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
    | Application Currency
    |--------------------------------------------------------------------------
    |
    | This value is the default currency of your store. This value is used when the
    | orders price is given. Your can use it on your frontend.
    |
    */

    'currency'  => env('CURRENCY_SYMBOL', 'XAF'),

    /*
    |--------------------------------------------------------------------------
    | Shopper Resource
    |--------------------------------------------------------------------------
    |
    | Automatically connect the stored links. For example js and css files.
    |
    */

    'resources' => [
        'stylesheets' => [],
        'scripts'     => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware Admin Panel
    |--------------------------------------------------------------------------
    |
    | Provide a convenient mechanism for filtering HTTP
    | requests entering your shopper application.
    |
    */

    'middleware' => [
        'web',
        Authenticate::class,
        'permission:view-backend',
        ResolveSidebars::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Api Default Authentication
    |--------------------------------------------------------------------------
    |
    | Define authentication you choose to protect your APIs. By default it is
    | the api_token which is used but you can replace it with JWT.
    |
    | Supported: "token", "jwt"
    |
    */

    'api_connection' => env('API_CONNECTION', 'token'),

    /*
    |--------------------------------------------------------------------------
    | API Middleware
    |--------------------------------------------------------------------------
    |
    | Provide a convenient mechanism for protect API
    | resources for your shopper application.
    |
    */

    'middleware_api' => [
        'auth:api',
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Define the way the Sidebar should be cached.
    | The cache store is defined by the Laravel.
    |
    | Available: "null" , "static", "user-based"
    |
    */

    'cache' => [
        'method'   => null,
        'duration' => 1440,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurations for the user
    |--------------------------------------------------------------------------
    |
    | User configuration to manage user access using spatie/laravel-permission.
    |
    */

    'users' => [
        'admin_role' => 'administrator',
        'default_role' => 'user',
    ]

];
