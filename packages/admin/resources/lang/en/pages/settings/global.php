<?php

declare(strict_types=1);

return [

    'menu' => 'Settings',
    'single' => 'setting',

    'empty_country_selector' => 'Please select a country',
    'logo_description' => 'The logo of your store that will be visible on your site. This assets will appear on your invoices.',
    'confirm_password_content' => 'For your security, please confirm your password to continue.',

    'general' => [
        'title' => 'Store Setting',
        'store_details' => 'Store details',
        'store_detail_summary' => 'Your customers will use this information to contact you.',
        'email_helper' => 'Your customers will use this address if they need to contact you.',
        'phone_number_helper' => 'Your customers will use this phone number if they need to call you directly.',
        'assets' => 'Assets',
        'assets_summary' => 'The logo and cover image of your store that will be visible on your site. This assets will appear on your invoices.',
        'store_address' => 'Store address',
        'store_address_summary' => 'This address will appear on your invoices. You can edit the address used.',
        'store_currency' => 'Store currency',
        'social_links' => 'Social links',
        'social_links_summary' => 'Information about your different accounts on social networks. Users will be able to contact you directly on your official pages.',
    ],

    'location' => [
        'menu' => 'Locations',
        'single' => 'location',
        'description' => 'Manage the places you stock inventory, fulfill orders, and sell products.',
        'count' => 'You’re using :count of the :total available locations.',
        'add' => 'Add location',
        'detail' => 'Details',
        'detail_summary' => 'Give this location a short name to make it easy to identify. You’ll see this name in areas like products.',
        'address' => 'Location address',
        'address_summary' => "Your location's complete information. Please put valide informations this can be accessible for your customers.",
        'set_default' => 'Set as default location',
        'set_default_summary' => 'Inventory at this location is available for sale online and will use as default',
        'is_default' => 'This is your default location. To change whether you fulfill online orders from this location, select another default location first.',
    ],

    'analytics' => [
        'google' => 'Google Analytics',
        'google_description' => 'Google Analytics allows you to track visitors to your site and generates reports that will help you with your marketing.',
        'gtag' => 'Google Tag Manager',
        'gtag_description' => 'Google Tag Manager allows marketing managers to easily add tags (Analytics, remarketing, etc.)',
        'pixel' => 'Facebook Pixel',
        'pixel_description' => 'Facebook Pixel helps you create ad campaigns to find new customers who are most like your buyers.',
        'no_json' => 'No json file added',
    ],

    'legal' => [
        'title' => 'Legal policy',
        'refund' => 'Refund policy',
        'privacy' => 'Privacy policy',
        'shipping' => 'Shipping policy',
        'terms_of_use' => 'Terms of use',
        'summary' => 'Define the :policy to which all users and consumers of the products in your store will be subject.',
    ],
];
