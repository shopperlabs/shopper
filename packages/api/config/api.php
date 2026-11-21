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
    | Token expiration
    |--------------------------------------------------------------------------
    |
    | Here you may set how many minutes a customer token issued by the store
    | authentication endpoints stays valid. A null or zero value keeps the token alive
    | until the customer logs out or resets their password. Expired tokens
    | answer 401 and are pruned daily.
    |
    */
    'token_expiration' => env('SHOPPER_API_TOKEN_EXPIRATION', 60 * 24 * 30),

    /*
    |--------------------------------------------------------------------------
    | Latest reviews
    |--------------------------------------------------------------------------
    |
    | Here you may set how many approved reviews the /store/reviews endpoint
    | returns, newest first. This endpoint is not paginated, so the limit
    | bounds every response.
    |
    */
    'reviews' => [
        'latest_limit' => 20,
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
