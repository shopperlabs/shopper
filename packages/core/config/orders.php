<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Order Number Generator
    |--------------------------------------------------------------------------
    |
    | Configuration for generating order numbers.
    | The default format produces: "ORD-20250123-000001"
    |
    | - `prefix`: The prefix before the order number (set to null to disable)
    | - `separator`: The character between each part
    | - `date_format`: PHP date format for the date part (set to null to disable)
    | - `start_sequence_from`: The starting number for the sequence
    | - `pad_length`: Total length of the sequence number (padded with pad_string)
    | - `pad_string`: Character used for padding
    |
    */

    'generator' => [
        'prefix' => 'ORD',
        'separator' => '-',
        'date_format' => 'Ymd',
        'start_sequence_from' => 1,
        'pad_length' => 6,
        'pad_string' => '0',
    ],

];
