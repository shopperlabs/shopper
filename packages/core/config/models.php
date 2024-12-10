<?php

declare(strict_types=1);

use Shopper\Core\Models;

return [

    /*
    |--------------------------------------------------------------------------
    | Brand Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to retrieve your brands. Of course,
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
    | Eloquent model should be used to retrieve your categories. Of course,
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
    | Eloquent model should be used to retrieve your collections. Of course,
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
    | Eloquent model should be used to retrieve your products. Of course,
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
    | Eloquent model should be used to retrieve your product variants. Of course,
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
    | Eloquent model should be used to retrieve channels. Of course,
    | If you want to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Channel Model.
    |
    */

    'channel' => Models\Channel::class,

];
