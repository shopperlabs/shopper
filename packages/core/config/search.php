<?php

declare(strict_types=1);

use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Product;
use Shopper\Core\Search\BrandIndexer;
use Shopper\Core\Search\CategoryIndexer;
use Shopper\Core\Search\CollectionIndexer;
use Shopper\Core\Search\OrderIndexer;
use Shopper\Core\Search\ProductIndexer;

return [

    /*
    |--------------------------------------------------------------------------
    | Search Engine Mapping
    |--------------------------------------------------------------------------
    |
    | Here you may define which Laravel Scout driver each searchable model
    | should use. Models that are not listed here will fall back to the
    | driver defined by the SCOUT_DRIVER environment variable.
    |
    */

    'engine_map' => [
        // Product::class => 'typesense',
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Indexers
    |--------------------------------------------------------------------------
    |
    | Every searchable model delegates its index name, indexed payload and
    | field definitions to an indexer class. You may swap any indexer for
    | your own implementation to customize the data sent to the engine.
    |
    */

    'indexers' => [
        Brand::class => BrandIndexer::class,
        Category::class => CategoryIndexer::class,
        Collection::class => CollectionIndexer::class,
        Order::class => OrderIndexer::class,
        Product::class => ProductIndexer::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Facets
    |--------------------------------------------------------------------------
    |
    | Here you may define the fields available for faceting when searching
    | through the shopper/search addon. Each facet may define a label and
    | any additional metadata returned alongside its values.
    |
    */

    'facets' => [
        Product::class => [
            'brand' => ['label' => 'Brand'],
            'categories' => ['label' => 'Categories'],
        ],
    ],

];
