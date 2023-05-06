<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | MapBox Api Token
    |--------------------------------------------------------------------------
    |
    | This will be displayed on the login page and in the sidebar's header.
    |
    */

    'mapbox_token' => env('MAPBOX_PUBLIC_TOKEN', null),

    /*
    |--------------------------------------------------------------------------
    | Shopper Locale Configuration
    |--------------------------------------------------------------------------
    |
    | Shopper PHP locale determines the default locale that will be used
    | by the model date format function ->formatLocalized().
    |
    */

    'locale' => 'en_EN',

    /*
    |--------------------------------------------------------------------------
    | Database Table Prefix
    |--------------------------------------------------------------------------
    |
    | This prefix table can be used for the prefix of each shopper
    | tables in your database. For example your products table will have
    | the current name `sp_products` if you are using 'sp_' as tables prefix.
    |
    | Eg: 'table_prefix' => 'sp_'
    |
    */

    'table_prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | Specifies the configuration for resource storage, such as users avatars
    | and upload files.
    |
    */

    'storage' => [

        'disks' => [

            /*
             * Avatars folder uploads picture.
             *
             * Create this disk under the `filesystems.php` config file if not exist.
             */
            'avatars' => 'avatars',

            /*
             * Uploads folders uploads files.
             *
             * Create this disk under the filesystems.php config file if not exist.
             */
            'uploads' => 'uploads',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Barcode type
    |--------------------------------------------------------------------------
    |
    | Allows you to choose what type of barcode you want to use for your products
    | This feature uses the milon/barcode package. The list of code types
    | is available here. https://github.com/milon/barcode#1d-barcodes
    |
    */

    'barcode_type' => 'C128',

];
