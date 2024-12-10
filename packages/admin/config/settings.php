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
            'name' => 'shopper::pages/settings/menu.general',
            'description' => 'shopper::pages/settings/menu.general_description',
            'icon' => 'untitledui-sliders',
            'route' => 'shopper.settings.shop',
            'permission' => null,
        ],
        [
            'name' => 'shopper::pages/settings/menu.staff',
            'description' => 'shopper::pages/settings/menu.staff_description',
            'icon' => 'untitledui-shield-separator',
            'route' => 'shopper.settings.users',
            'permission' => null,
        ],
        [
            'name' => 'shopper::pages/settings/menu.location',
            'description' => 'shopper::pages/settings/menu.location_description',
            'icon' => 'untitledui-marker-pin-flag',
            'route' => 'shopper.settings.locations',
            'permission' => null,
        ],
        [
            'name' => 'shopper::pages/settings/menu.payment',
            'description' => 'shopper::pages/settings/menu.payment_description',
            'icon' => 'untitledui-coins-hand',
            'route' => 'shopper.settings.payments',
            'permission' => null,
        ],
        [
            'name' => 'shopper::pages/settings/menu.legal',
            'description' => 'shopper::pages/settings/menu.legal_description',
            'icon' => 'untitledui-file-lock-02',
            'route' => 'shopper.settings.legal',
            'permission' => null,
        ],
        [
            'name' => 'shopper::pages/settings/menu.zone',
            'description' => 'shopper::pages/settings/menu.zone_description',
            'icon' => 'untitledui-globe-05',
            'route' => 'shopper.settings.zones',
            'permission' => null,
        ],
    ],

];
