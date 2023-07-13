<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Setting Menu
    |--------------------------------------------------------------------------
    |
    | The menu for the generation of the page settings and layout.
    | BladeUIKit Heroicon is the icon used. See https://blade-ui-kit.com/blade-icons?set=1
    |
    */

    'items' => [
        [
            'name' => 'General',
            'description' => 'View and update your store information.',
            'icon' => 'heroicon-o-cog',
            'route' => 'shopper.settings.shop',
            'permission' => null,
        ],
        [
            'name' => 'Staff & permissions',
            'description' => 'View and manage what staff can see or do in your store.',
            'icon' => 'heroicon-o-users',
            'route' => 'shopper.settings.users',
            'permission' => null,
        ],
        [
            'name' => 'Locations',
            'description' => 'Manage the places you stock inventory and sell products.',
            'icon' => 'heroicon-o-location-marker',
            'route' => 'shopper.settings.inventories.index',
            'permission' => null,
        ],
        [
            'name' => 'Shipping and delivery',
            'description' => 'Manage how you ship orders to customers.',
            'icon' => 'heroicon-o-truck',
            'route' => null,
            'permission' => null,
        ],
        [
            'name' => 'Analytics',
            'description' => 'Get a better understanding of where your traffic is coming from.',
            'icon' => 'heroicon-o-chart-bar',
            'route' => 'shopper.settings.analytics',
            'permission' => null,
        ],
        [
            'name' => 'Payment methods',
            'description' => 'Add different payment methods for your customers.',
            'icon' => 'heroicon-o-credit-card',
            'route' => 'shopper.settings.payments',
            'permission' => null,
        ],
        [
            'name' => 'Legal',
            'description' => 'Manage your store\'s legal pages such as privacy, terms.',
            'icon' => 'heroicon-o-lock-closed',
            'route' => 'shopper.settings.legal',
            'permission' => null,
        ],
    ],
];
