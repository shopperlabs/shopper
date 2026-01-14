<?php

declare(strict_types=1);

use Shopper\Core\Models;

return [

    /*
    |--------------------------------------------------------------------------
    | Address Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to retrieve user shipping / billing address.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Address Model.
    |
    */

    'address' => Models\Address::class,

    /*
    |--------------------------------------------------------------------------
    | Brand Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interacts with your brands.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Brand Model.
    |
    */

    'brand' => Models\Brand::class,

    /*
    |--------------------------------------------------------------------------
    | Category Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interacts with your categories.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Category Model.
    |
    */

    'category' => Models\Category::class,

    /*
    |--------------------------------------------------------------------------
    | Collection Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interacts with your collections.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Collection Model.
    |
    */

    'collection' => Models\Collection::class,

    /*
    |--------------------------------------------------------------------------
    | Product Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interacts with your products.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Product Model.
    |
    */

    'product' => Models\Product::class,

    /*
    |--------------------------------------------------------------------------
    | Product Variant Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interacts with your product variants.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\ProductVariant Model.
    |
    */

    'variant' => Models\ProductVariant::class,

    /*
    |--------------------------------------------------------------------------
    | Channel Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interacts with your channels.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Channel Model.
    |
    */

    'channel' => Models\Channel::class,

    /*
    |--------------------------------------------------------------------------
    | Channel Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interact with inventories.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Inventory Model.
    |
    */

    'inventory' => Models\Inventory::class,

    /*
    |--------------------------------------------------------------------------
    | Channel Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interact with your orders.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Order Model.
    |
    */

    'order' => Models\Order::class,

];
