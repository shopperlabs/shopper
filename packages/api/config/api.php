<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Default page size applied by collection endpoints, with a hard ceiling
    | a client cannot exceed through the page[size] parameter. The page number
    | is capped at max_page; use page[cursor] to walk past it at a flat cost.
    |
    */
    'pagination' => [
        'per_page' => (int) env('SHOPPER_API_PER_PAGE', 15),
        'max_per_page' => 100,
        'max_page' => 100,
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
                'q' => ['scope', 'matching'],
                'featured' => 'exact',
                'category' => 'scope',
                'collection' => ['scope', 'collection'],
                'brand' => ['scope', 'byBrand'],
                'tag' => ['scope', 'tag'],
                'option' => ['scope', 'option'],
            ],
            'sorts' => ['name', 'created_at', 'published_at', 'price' => ['field', 'min_price']],
            'includes' => [
                'brand',
                'variants',
                'categories',
                'collections',
                'options',
                'relatedProducts' => Shopper\Api\Http\Includes\PublicProducts::class,
                'rating' => Shopper\Api\Http\Includes\RatingAggregate::class,
            ],
            'include_loads' => [
                'variants' => ['variants.prices.currency', 'variants.values.attribute'],
                'options' => ['options.values', 'attributeProducts.media'],
            ],
        ],
        'category' => [
            'filters' => ['name' => 'partial'],
            'sorts' => ['name', 'position'],
            'includes' => [
                'parent',
                'children',
                'products' => Shopper\Api\Http\Includes\PublicProducts::class,
            ],
        ],
        'collection' => [
            'filters' => ['name' => 'partial'],
            'sorts' => ['name'],
            'includes' => [
                'products' => Shopper\Api\Http\Includes\PublicProducts::class,
            ],
        ],
        'brand' => [
            'filters' => ['name' => 'partial'],
            'sorts' => ['name', 'position'],
            'includes' => [
                'products' => Shopper\Api\Http\Includes\PublicProducts::class,
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
        'order' => [
            'filters' => ['status' => 'exact'],
            'sorts' => ['created_at'],
            'includes' => ['items'],
        ],
        'review' => [
            'filters' => ['rating' => 'exact'],
            'sorts' => ['created_at', 'rating'],
            'includes' => [],
            'latest_limit' => 20,
        ],
        'legal' => [
            'filters' => ['title' => 'partial'],
            'sorts' => ['title', 'updated_at'],
            'includes' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Public store settings
    |--------------------------------------------------------------------------
    |
    | Here you may list the setting keys the /store/settings endpoint returns.
    | The settings table also holds administration keys, so the endpoint reads
    | from this allowlist and never from the table itself. Add your own keys to
    | expose them; a key that was never filled in comes back as null.
    |
    | The `country_id`, `default_currency_id`, `currencies`, `logo`, `cover`
    | and `social_links` keys are resolved to their public shape (ISO code,
    | currency code, media URL) rather than the internal value.
    |
    */
    'settings' => [
        'expose' => [
            'name',
            'legal_name',
            'about',
            'email',
            'phone_number',
            'street_address',
            'city',
            'postal_code',
            'state',
            'country_id',
            'logo',
            'cover',
            'social_links',
            'default_currency_id',
            'currencies',
        ],

        'max_age' => 300,
    ],
];
