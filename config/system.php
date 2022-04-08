<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Brand Name
    |--------------------------------------------------------------------------
    |
    | This will be displayed on the login page and in the sidebar's header.
    |
    */

    'brand' => null,

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
    | Shopper Resource
    |--------------------------------------------------------------------------
    |
    | Automatically connect the stored links. For example js and css files.
    |
    */

    'resources' => [
        'stylesheets' => [],
        'scripts'     => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Define the way the Sidebar should be cached.
    | The cache store defined by the Laravel.
    |
    | Available: "null" , "static", "user-based"
    |
    */

    'cache' => [
        'method'   => null,
        'duration' => 1440,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurations for the user
    |--------------------------------------------------------------------------
    |
    | User configuration to manage user access using spatie/laravel-permission.
    |
    */

    'users' => [
        'admin_role' => 'administrator',
        'default_role' => 'user',
    ],

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
    | Shopper Controllers config
    |--------------------------------------------------------------------------
    |
    | If you want extends your shopper admin panel with great features,
    | Here you can specify custom Controller Namespace and Shopper
    | RouteServiceProvider will load all your controllers.
    |
    */

    'controllers' => [

        'namespace' => 'App\\Http\\Controllers\\Shopper',

    ],

    /*
    |--------------------------------------------------------------------------
    | Shopper Ecommerce Model
    |--------------------------------------------------------------------------
    |
    | Here you may specify the ecommerce class used by Shopper for the
    | management of your website. You can replace the models with classes
    | which is in a different namespace.
    |
    */

    'models' => [

        /*
         * Eloquent model should be used to retrieve your brands. Of course,
         * it is often just the "Brand" model but you may use whatever you like.
         *
         * The model you want to use as a Brand model needs to extends the
         * `\Shopper\Framework\Models\Shop\Product\Brand` model.
         */

        'brand' => \Shopper\Framework\Models\Shop\Product\Brand::class,

        /*
         * Eloquent model should be used to retrieve your categories. Of course,
         * it is often just the "Category" model but you may use whatever you like.
         *
         * The model you want to use as a Category model needs to extends the
         * `\Shopper\Framework\Models\Shop\Product\Category` model.
         */

        'category'  => \Shopper\Framework\Models\Shop\Product\Category::class,

        /*
         * Eloquent model should be used to retrieve your collections. Of course,
         * it is often just the "Collection" model but you may use whatever you like.
         *
         * The model you want to use as a Collection model needs to extends the
         * `\Shopper\Framework\Models\Shop\Product\Collection` model.
         */

        'collection'  => \Shopper\Framework\Models\Shop\Product\Collection::class,

        /*
         * Eloquent model should be used to retrieve your products. Of course,
         * it is often just the "Product" model but you may use whatever you like.
         *
         * The model you want to use as a Product model needs to extends the
         * `\Shopper\Framework\Models\Shop\Product\Product` model.
         */

        'product'  => \Shopper\Framework\Models\Shop\Product\Product::class,

    ],

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
