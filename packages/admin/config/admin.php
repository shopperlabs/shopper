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
    | Roles
    |--------------------------------------------------------------------------
    |
    | User configuration to manage user access using spatie/laravel-permission.
    | We recommend that you do not update this configuration in production,
    | this could cause a bug on your system.
    |
    */

    'roles' => [
        'admin' => 'administrator',
        'manager' => 'manager',
        'user' => 'user',
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
    | Brand Primary UI color
    |--------------------------------------------------------------------------
    |
    | By default on some elements, filament does not take into account the "primary"
    | color, to correct this after changing your primary color in your tailwind file
    | you must also change it here.
    |
    */

    'primary_color' => Color::Blue,

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | The list of locales available in the admin panel. Each entry maps a
    | locale code to its display label and the corresponding country flag code
    | (ISO 3166-1 alpha-2, lowercase) used to resolve the flag SVG.
    |
    | Example:
    |   'de' => ['label' => 'Deutsch', 'flag' => 'de'],
    |
    */

    'locales' => [
        'en' => ['label' => 'English', 'flag' => 'gb'],
        'fr' => ['label' => 'Français', 'flag' => 'fr'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching Blade Icons
    |--------------------------------------------------------------------------
    |
    | Here you may configure the caching of the icon picker component. Since
    | icon packs often ship with a lot of icons, scanning them on every
    | request can be slow. Set "cache" to false to disable it entirely.
    |
    */

    'icon-picker' => [
        'cache' => true,
        'duration' => '7 days',
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Notifications
    |--------------------------------------------------------------------------
    |
    | In-app notifications displayed in the admin topbar bell. When enabled,
    | domain events (new order, paid order, failed payment, ...) are persisted
    | for administrators and surfaced in real time.
    |
    | The "polling_interval" controls how often the bell refreshes its content.
    | Set it to null to disable polling entirely and rely solely on broadcasting
    | through Laravel Echo (see the broadcast notifications documentation).
    |
    */

    'notifications' => [
        'database' => [
            'enabled' => false,
            'polling_interval' => '30s',
        ],
    ],

];
