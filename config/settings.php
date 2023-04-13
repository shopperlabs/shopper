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
            'name' => __('General'),
            'description' => __('View and update your store information.'),
            'icon' => 'heroicon-o-cog',
            'route' => 'shopper.settings.shop',
            'permission' => null,
        ],
        [
            'name' => __('Staff & permissions'),
            'description' => __('View and manage what staff can see or do in your store.'),
            'icon' => 'heroicon-o-users',
            'route' => 'shopper.settings.users',
            'permission' => null,
        ],
        [
            'name' => __('Email'),
            'description' => __('Manage email notifications that will be sent to your customers.'),
            'icon' => 'heroicon-o-mail',
            'route' => 'shopper.settings.mails',
            'permission' => null,
        ],
        [
            'name' => __('Locations'),
            'description' => __('Manage the places you stock inventory and sell products.'),
            'icon' => 'heroicon-o-location-marker',
            'route' => 'shopper.settings.inventories.index',
            'permission' => null,
        ],
        [
            'name' => __('Attributes'),
            'description' => __('Manage additional attributes for your products.'),
            'icon' => 'heroicon-o-clipboard-list',
            'route' => 'shopper.settings.attributes.index',
            'permission' => null,
        ],
        [
            'name' => __('Shipping and delivery'),
            'description' => __('Manage how you ship orders to customers.'),
            'icon' => 'heroicon-o-truck',
            'route' => null,
            'permission' => null,
        ],
        [
            'name' => __('Integrations'),
            'description' => __('Connect with third-party tools that you’re already using.'),
            'icon' => 'heroicon-o-clipboard-list',
            'route' => null,
            'permission' => null,
        ],
        [
            'name' => __('Analytics'),
            'description' => __('Get a better understanding of where your traffic is coming from.'),
            'icon' => 'heroicon-o-chart-bar',
            'route' => 'shopper.settings.analytics',
            'permission' => null,
        ],
        [
            'name' => __('Taxes'),
            'description' => __('Manage how your store charges taxes.'),
            'icon' => 'heroicon-o-receipt-tax',
            'route' => null,
            'permission' => null,
        ],
        [
            'name' => __('Payment methods'),
            'description' => __('Add different payment methods for your customers.'),
            'icon' => 'heroicon-o-credit-card',
            'route' => 'shopper.settings.payments',
            'permission' => null,
        ],
        [
            'name' => __('Files'),
            'description' => __('Manage store assets (images, videos and documents).'),
            'icon' => 'heroicon-o-paper-clip',
            'route' => null,
            'permission' => null,
        ],
        [
            'name' => __('Legal'),
            'description' => __('Manage your store\'s legal pages such as privacy, terms.'),
            'icon' => 'heroicon-o-lock-closed',
            'route' => 'shopper.settings.legal',
            'permission' => null,
        ],
    ],
];
