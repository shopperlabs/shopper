<?php

declare(strict_types=1);

use Shopper\Livewire;

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Pages
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'collection-index' => Livewire\Pages\Collection\Index::class,
        'collection-edit' => Livewire\Pages\Collection\Edit::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'collections.products' => Livewire\Components\Collection\CollectionProducts::class,

        'modals.products-list' => Livewire\Modals\CollectionProductsList::class,

        'slide-overs.collection-rules' => Livewire\SlideOvers\CollectionRules::class,
        'slide-overs.add-collection-form' => Livewire\SlideOvers\AddCollectionForm::class,
    ],

];
