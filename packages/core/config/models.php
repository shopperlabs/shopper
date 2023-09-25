<?php

declare(strict_types=1);

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

    'brand' => \Shopper\Core\Models\Brand::class,

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

    'category' => \Shopper\Core\Models\Category::class,

    /*
    |--------------------------------------------------------------------------
    | Collection Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to retrieve your collections. Of course,
    | if you want to change this to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Collection model.
    |
    */

    'collection' => \Shopper\Core\Models\Collection::class,

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

    'product' => \Shopper\Core\Models\Product::class,

    /*
    |--------------------------------------------------------------------------
    | Channel Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model should be used to retrieve your channels. Of course,
    | If you want to change this to use a custom model, your model needs to extends the
    | \Shopper\Core\Models\Channel model.
    |
    */

    'channel' => \Shopper\Core\Models\Channel::class,

];
