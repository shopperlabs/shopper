<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Media Mine Types
    |--------------------------------------------------------------------------
    |
    |
    */

    'accepts_mime_types' => [
        'image/jpg',
        'image/jpeg',
        'image/png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Conversion
    |--------------------------------------------------------------------------
    |
    |
    */

    'conversions' => [
        'large' => [
            'width' => 800,
            'height' => 800,
        ],
        'medium' => [
            'width' => 500,
            'height' => 500,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback image URL
    |--------------------------------------------------------------------------
    |
    | If your media collection does not contain any items, this image should be displayed
    |
    */

    'fallback_url' => '/shopper/images/placeholder.jpg',

];
