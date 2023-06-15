<?php

declare(strict_types=1);

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

    'prefix' => env('SHOPPER_PREFIX', 'cpanel'),

    /*
    |--------------------------------------------------------------------------
    | Shopper Domain
    |--------------------------------------------------------------------------
    |
    | You may change the domain where Shopper should be active. If the domain
    | is empty, all domains will be valid.
    |
    */

    'domain' => env('SHOPPER_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Admin Brand Name
    |--------------------------------------------------------------------------
    |
    | This will be displayed on the login page and in the sidebar's header.
    |
    */

    'brand' => null,

    /*
    |--------------------------------------------------------------------------
    | Default Avatar UI color
    |--------------------------------------------------------------------------
    |
    | Default hexadecimal colors to be used for user avatars
    | Don't add # in front of the color code.
    | Eg. In case of #fff000 use fff000
    |
    */

    'avatar' => [
        'color' => '1d4ed8',
        'bg_color' => 'dbeafe',
    ],

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
        'scripts' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Shopper Controllers config
    |--------------------------------------------------------------------------
    |
    | If you want extends your shopper admin panel with great features,
    | Here you can specify custom Controller Namespace and Shopper
    | RouteServiceProvider will load all your controllers.
    |
    */

    'controllers' => [

        'namespace' => 'App\\Http\\Controllers\\Shopper',

    ],

];
