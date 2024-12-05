<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Storage Disk
    |--------------------------------------------------------------------------
    |
    | Specifies the configuration for resources storage, this will be to store
    | all media of your products, brands, categories, etc.
    |
    */

    'storage' => [
        'collection_name' => 'uploads',
        'thumbnail_collection' => 'thumbnail',
        'disk_name' => 'public',
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Mine Types
    |--------------------------------------------------------------------------
    |
    | Spatie media-library mime types supported when you upload any image
    |
    */

    'accepts_mime_types' => [
        'image/jpg',
        'image/jpeg',
        'image/png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Filesize
    |--------------------------------------------------------------------------
    |
    | This setting allows you to define the maximum size of your images.
    |
    */

    'max_size' => [
        'thumbnail' => 1024, // Default size for thumbnail image (1MB). This config is the same for brand, category and collection thumbnail
        'images' => 2048, // Default size for individual collection image for product (2MB)
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Conversion
    |--------------------------------------------------------------------------
    |
    | Image conversion lets you display images according to the dimensions of
    | the product photos you have in your store.
    |
    | This configuration allows you to customize each poster according to
    | the size on the desired section of your store and adapt it directly
    | to your design.
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

];
