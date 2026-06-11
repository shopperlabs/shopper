<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Default page size applied by collection endpoints, with a hard ceiling
    | a client cannot exceed through the page[size] parameter.
    |
    */
    'pagination' => [
        'per_page' => (int) env('SHOPPER_API_PER_PAGE', 15),
        'max_per_page' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource query allowlists
    |--------------------------------------------------------------------------
    |
    | Filters, sorts, and includes a client is allowed to request per resource
    | (spatie/laravel-query-builder). Sparse fieldsets (fields[type]) are handled
    | on the response side by the JSON:API resources. Extend these to expose more
    | of your own columns and relations.
    |
    */
    'resources' => [
        'product' => [
            'filters' => [
                'name' => 'partial',
                'sku' => 'exact',
                'q' => ['scope', 'search'],
                'category' => 'scope',
                'collection' => ['scope', 'collection'],
                'brand' => ['scope', 'byBrand'],
                'tag' => ['scope', 'tag'],
                'option' => ['scope', 'option'],
            ],
            'sorts' => ['name', 'created_at', 'published_at'],
            'includes' => [
                'brand',
                'variants',
                'categories',
                'collections',
                'options',
                'relatedProducts',
                'rating' => Shopper\Api\Http\Includes\RatingAggregate::class,
            ],
            'include_loads' => [
                'variants' => ['variants.prices.currency', 'variants.values.attribute'],
                'options' => ['options.values'],
                'relatedProducts' => ['relatedProducts.prices.currency'],
            ],
        ],
        'category' => [
            'filters' => ['name' => 'partial'],
            'sorts' => ['name', 'position'],
            'includes' => ['parent', 'children', 'products'],
            'include_loads' => [
                'products' => ['products.prices.currency'],
            ],
        ],
        'collection' => [
            'filters' => ['name' => 'partial'],
            'sorts' => ['name'],
            'includes' => [],
        ],
        'brand' => [
            'filters' => ['name' => 'partial'],
            'sorts' => ['name', 'position'],
            'includes' => ['products'],
            'include_loads' => [
                'products' => ['products.prices.currency'],
            ],
        ],
        'attribute' => [
            'filters' => ['name' => 'partial'],
            'sorts' => ['name', 'position'],
            'includes' => [],
        ],
        'country' => [
            'filters' => [
                'name' => 'partial',
                'cca2' => 'exact',
                'cca3' => 'exact',
                'region' => 'exact',
                'zone' => ['exact', 'zones.code'],
            ],
            'sorts' => ['name'],
            'includes' => ['zones'],
        ],
        'zone' => [
            'filters' => ['name' => 'partial', 'code' => 'exact'],
            'sorts' => ['name'],
            'includes' => ['currency', 'countries'],
        ],
        'currency' => [
            'filters' => ['name' => 'partial', 'code' => 'exact'],
            'sorts' => ['name', 'code'],
            'includes' => [],
        ],
    ],
];
