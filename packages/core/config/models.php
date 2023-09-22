<?php

declare(strict_types=1);

use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Product;

return [

    /*
    |--------------------------------------------------------------------------
    | Brand Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to retrieve your brands. Of course,
    | If you want to change this to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Brand model.
    |
    */

    'brand' => Brand::class,

    /*
    |--------------------------------------------------------------------------
    | Category Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to retrieve your categories. Of course,
    | If you want to change this to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Category model.
    |
    */

    'category' => Category::class,

    /*
    |--------------------------------------------------------------------------
    | Collection Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to retrieve your collections.  Of course,
    | if you want to change this to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Collection model.
    |
    */

    'collection' => Collection::class,

    /*
    |--------------------------------------------------------------------------
    | Product Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to retrieve your products. Of course,
    | If you want to change this to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Product model.
    |
    */

    'product' => Product::class,

    /*
    |--------------------------------------------------------------------------
    | Channel Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to retrieve your products. Of course,
    | If you want to change this to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Channel model.
    |
    */

    'channel' => Channel::class,

];
