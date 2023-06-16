<?php

declare(strict_types=1);

use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Product;

return [

    /*
     * Eloquent model should be used to retrieve your brands. Of course,
     * it is often just the "Brand" model but you may use whatever you like.
     *
     * The model you want to use as a Brand model needs to extends the
     * `\Shopper\Core\Models\Brand` model.
     */
    'brand' => Brand::class,

    /*
     * Eloquent model should be used to retrieve your categories. Of course,
     * it is often just the "Category" model but you may use whatever you like.
     *
     * The model you want to use as a Category model needs to extends the
     * `\Shopper\Core\Models\Category` model.
     */
    'category' => Category::class,

    /*
     * Eloquent model should be used to retrieve your collections. Of course,
     * it is often just the "Collection" model but you may use whatever you like.
     *
     * The model you want to use as a Collection model needs to extends the
     * `\Shopper\Core\Models\Collection` model.
     */
    'collection' => Collection::class,

    /*
     * Eloquent model should be used to retrieve your products. Of course,
     * it is often just the "Product" model but you may use whatever you like.
     *
     * The model you want to use as a Product model needs to extends the
     * `\Shopper\Core\Models\Product` model.
     */
    'product' => Product::class,

    /*
     * Eloquent model should be used to retrieve your channels. Of course,
     * it is often just the "Channel" model but you may use whatever you like.
     *
     * The model you want to use as a Channel model needs to extends the
     * `\Shopper\Core\Models\Channel` model.
     */
    'channel' => Channel::class,

];
