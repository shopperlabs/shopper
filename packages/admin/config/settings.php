<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Setting Menu
    |--------------------------------------------------------------------------
    |
    | The menu for the generation of the settings page.
    | UntitledUI is the icon used. See https://blade-ui-kit.com/blade-icons?set=74
    |
    | 'icon' => 'name_of_the_set_icon'
    |
    */

    'items' => [
        [
            'name' => 'General',
            'description' => 'View and update your store information.',
            'icon' => 'untitledui-sliders',
            'route' => 'shopper.settings.shop',
            'permission' => null,
        ],
        [
            'name' => 'Staff & permissions',
            'description' => 'View and manage what staff can see or do in your store.',
            'icon' => 'untitledui-shield-separator',
            'route' => 'shopper.settings.users',
            'permission' => null,
        ],
        [
            'name' => 'Locations',
            'description' => 'Manage the places you stock inventory and sell products.',
            'icon' => 'untitledui-marker-pin-flag',
            'route' => 'shopper.settings.inventories',
            'permission' => null,
        ],
        //        [
        //            'name' => 'Analytics',
        //            'description' => 'Get a better understanding of where your traffic is coming from.',
        //            'icon' => 'untitledui-pie-chart',
        //            'route' => 'shopper.settings.analytics',
        //            'permission' => null,
        //        ],
        [
            'name' => 'Payment methods',
            'description' => 'Add different payment methods for your customers.',
            'icon' => 'untitledui-coins-hand',
            'route' => 'shopper.settings.payments',
            'permission' => null,
        ],
        [
            'name' => 'Legal',
            'description' => "Manage your store's legal pages such as privacy, terms.",
            'icon' => 'untitledui-file-lock-02',
            'route' => 'shopper.settings.legal',
            'permission' => null,
        ],
    ],

];
