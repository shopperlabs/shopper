<?php

declare(strict_types=1);

use Filament\Support\Colors\Color;

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
    | Pages
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Shopper save page components
    |
    */

    'pages' => [
        'namespace' => 'App\\Livewire\\Shopper',
        'view_path' => resource_path('views/livewire/shopper'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Brand Logo
    |--------------------------------------------------------------------------
    |
    | This will be displayed on the login page and in the sidebar's header.
    | This is your site's logo. It will be loaded directly from the public folder
    | Ex: '/images/logo.svg'
    |
    */

    'brand' => null,

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | This is the path to the favicon used for pages in the admin panel.
    |
    */

    'favicon' => null,

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
    | Filament Primary UI color
    |--------------------------------------------------------------------------
    |
    | By default on some elements filament does not take into account the "primary"
    | color, to correct this after changing your primary color in your tailwind file
    | you must also change it here.
    |
    */

    'filament_color' => Color::Blue,

    /*
    |--------------------------------------------------------------------------
    | Additional Resources
    |--------------------------------------------------------------------------
    |
    | Automatically load the assets links. For js and css files.
    |
    | Example:
    |
    | 'stylesheets' => [
    |    asset('/my-script.css'),
    |   'https://example.com/my-style.css'
    | ]
    |
    */

    'resources' => [
        'stylesheets' => [],
        'scripts' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Inventory Limit
    |--------------------------------------------------------------------------
    |
    | Limits the number of inventories your store can manage, making it easier
    | to manage your stock.
    |
    */

    'inventory_limit' => 4,

    /*
    |--------------------------------------------------------------------------
    | Caching Blade Icons
    |--------------------------------------------------------------------------
    |
    | This section lets you configure the caching option of the icon picker component.
    |
    | Since icon packs are often packed with a lots of icons,
    | searching through all of them can take quite a lot of time, which is
    | why the plugin caches each field with it's configuration and search queries.
    |
    */

    'icon-picker' => [
        'cache' => true,
        'duration' => '7 days',
    ],

];
