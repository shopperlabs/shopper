<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Admin & User Role
    |--------------------------------------------------------------------------
    |
    | User configuration to manage user access using spatie/laravel-permission.
    | We recommend that you do not update this configuration in production,
    | this could cause a bug on your system.
    |
    */

    'users' => [
        'admin_role' => 'administrator',
        'default_role' => 'user',
    ],

    /*
    |--------------------------------------------------------------------------
    | MapBox Api Token
    |--------------------------------------------------------------------------
    |
    | By default to load Map component Shopper use Mapbox.
    | This key will be use to display the map
    |
    */

    'mapbox_token' => env('MAPBOX_PUBLIC_TOKEN', null),

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
    | Storage Disk
    |--------------------------------------------------------------------------
    |
    | Specifies the configuration for resources storage, this will be to store
    | all media of your products, brands, categories, etc.
    |
    */

    'storage' => [
        'collection_name' => 'uploads',
        'disk_name' => 'public',
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
