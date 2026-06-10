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
];
