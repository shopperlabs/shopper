<?php

declare(strict_types=1);

return [

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

    /*
    |--------------------------------------------------------------------------
    | Product Import
    |--------------------------------------------------------------------------
    |
    | Here you may define how many products are processed per queued job
    | when importing products from a CSV file or an external platform.
    |
    */

    'import' => [
        'chunk_size' => 25,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Here you may define how long, in seconds, the category tree stays
    | cached. Saving or deleting a category refreshes it, so this duration
    | only bounds a change made without going through the models. Set it to
    | null to always read the tree from the database.
    |
    */

    'cache' => [
        'category_tree' => 3600,
    ],

];
