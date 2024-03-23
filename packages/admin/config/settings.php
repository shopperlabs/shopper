<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Setting Menu
    |--------------------------------------------------------------------------
    |
    | The menu for the generation of the settings page.
    | BladeUIKit UntitledUI is the icon used. See https://blade-ui-kit.com/blade-icons?set=74
    |
    */

    'items' => [
        [
            'name' => 'General',
            'description' => 'View and update your store information.',
            'icon' => svg('untitledui-sliders', 'h-6 w-6'),
            'route' => 'shopper.settings.shop',
            'permission' => null,
        ],
        [
            'name' => 'Staff & permissions',
            'description' => 'View and manage what staff can see or do in your store.',
            'icon' => svg('untitledui-shield-separator', 'h-6 w-6'),
            'route' => 'shopper.settings.users',
            'permission' => null,
        ],
        [
            'name' => 'Locations',
            'description' => 'Manage the places you stock inventory and sell products.',
            'icon' => svg('untitledui-marker-pin-flag', 'h-6 w-6'),
            'route' => 'shopper.settings.inventories',
            'permission' => null,
        ],
        [
            'name' => 'Analytics',
            'description' => 'Get a better understanding of where your traffic is coming from.',
            'icon' => svg('untitledui-pie-chart', 'h-6 w-6'),
            'route' => 'shopper.settings.analytics',
            'permission' => null,
        ],
        [
            'name' => 'Payment methods',
            'description' => 'Add different payment methods for your customers.',
            'icon' => svg('untitledui-coins-hand', 'h-6 w-6'),
            'route' => 'shopper.settings.payments',
            'permission' => null,
        ],
        [
            'name' => 'Legal',
            'description' => "Manage your store's legal pages such as privacy, terms.",
            'icon' => svg('untitledui-file-lock-02', 'h-6 w-6'),
            'route' => 'shopper.settings.legal',
            'permission' => null,
        ],
    ],

];
