<?php

declare(strict_types=1);

use Shopper\Models;

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

    'address' => Shopper\Core\Models\Address::class,

    /*
    |--------------------------------------------------------------------------
    | Brand Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interacts with your brands.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Models\Brand Model.
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
    | \Shopper\Models\Category Model.
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
    | \Shopper\Models\Collection Model.
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
    | \Shopper\Models\Product Model.
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
    | \Shopper\Models\ProductVariant Model.
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

    'channel' => Shopper\Core\Models\Channel::class,

    /*
    |--------------------------------------------------------------------------
    | Inventory Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interact with inventories.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Inventory Model.
    |
    */

    'inventory' => Shopper\Core\Models\Inventory::class,

    /*
    |--------------------------------------------------------------------------
    | Order Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interact with your orders.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Order Model.
    |
    */

    'order' => Shopper\Core\Models\Order::class,

    /*
    |--------------------------------------------------------------------------
    | Supplier Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interact with your suppliers.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Supplier Model.
    |
    */

    'supplier' => Shopper\Core\Models\Supplier::class,

    /*
    |--------------------------------------------------------------------------
    | Tax Zone Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interact with your tax zones.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\TaxZone Model.
    |
    */

    'tax_zone' => Shopper\Core\Models\TaxZone::class,

    /*
    |--------------------------------------------------------------------------
    | Tax Rate Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to interact with your tax rates.
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\TaxRate Model.
    |
    */

    'tax_rate' => Shopper\Core\Models\TaxRate::class,

];
