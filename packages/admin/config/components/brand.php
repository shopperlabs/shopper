<?php

declare(strict_types=1);

use Shopper\Livewire\Components;
use Shopper\Livewire\Pages;

return [

    'pages' => [],

    'components' => [
        'brands.browse' => Components\Brands\Browse::class,
        'brands.create' => Components\Brands\Create::class,
        'brands.edit' => Components\Brands\Edit::class,
    ],

];
