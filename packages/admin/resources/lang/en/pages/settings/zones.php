<?php

declare(strict_types=1);

return [

    'title' => 'Zones',
    'single' => 'Zone',
    'description' => 'Zones represent the markets in which you will be operating.',
    'add_action' => 'Add zone',
    'empty_heading' => 'No zones',
    'providers' => 'Providers',
    'providers_description' => 'Add the delivery and payment methods that should be available in this area.',
    'currency_help' => 'The main currency of this zone, from the list of currencies configured when you created your store.',
    'empty_detail_heading' => 'No zone selected',
    'empty_detail_description' => "Once you've selected a zone, all its information will be available here",

    'shipping_options' => [
        'title' => 'Shipping Options',
        'description' => 'Enter specifics about available zone shipment methods.',
        'option_visibility' => 'Enable or disable the shipping option visibility in store.',
        'add' => 'Add Option',
        'add_heading' => 'Shipping Option for the :name zone',
        'update' => 'Update option (:name)',
        'empty_heading' => 'No shipping option available',
    ],

];
