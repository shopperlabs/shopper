<?php

declare(strict_types=1);

return [

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
    | Database Table Prefix
    |--------------------------------------------------------------------------
    |
    | This prefix table can be used for the prefix of each shopper
    | table in your database. For example, your products table will have
    | the current name `shopper_products` if you are using 'shopper _' as a table prefix.
    |
    | Eg: 'table_prefix' => 'shopper_'
    |
    */

    'table_prefix' => 'sh_',

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
