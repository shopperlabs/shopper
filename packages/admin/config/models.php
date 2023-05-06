<?php

use Shopper\Framework\Models\Shop\Product;

return [

    /*
     * Eloquent model should be used to retrieve your brands. Of course,
     * it is often just the "Brand" model but you may use whatever you like.
     *
     * The model you want to use as a Brand model needs to extends the
     * `\Shopper\Framework\Models\Shop\Product\Brand` model.
     */

    'brand' => Product\Brand::class,

    /*
     * Eloquent model should be used to retrieve your categories. Of course,
     * it is often just the "Category" model but you may use whatever you like.
     *
     * The model you want to use as a Category model needs to extends the
     * `\Shopper\Framework\Models\Shop\Product\Category` model.
     */

    'category' => Product\Category::class,

    /*
     * Eloquent model should be used to retrieve your collections. Of course,
     * it is often just the "Collection" model but you may use whatever you like.
     *
     * The model you want to use as a Collection model needs to extends the
     * `\Shopper\Framework\Models\Shop\Product\Collection` model.
     */

    'collection' => Product\Collection::class,

    /*
     * Eloquent model should be used to retrieve your products. Of course,
     * it is often just the "Product" model but you may use whatever you like.
     *
     * The model you want to use as a Product model needs to extends the
     * `\Shopper\Framework\Models\Shop\Product\Product` model.
     */

    'product' => Product\Product::class,

];
