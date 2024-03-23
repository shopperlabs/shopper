<?php

declare(strict_types=1);

use Shopper\Enum\FeatureState;

return [

    /*
    |--------------------------------------------------------------------------
    | Shopper Features
    |--------------------------------------------------------------------------
    |
    | If you want extends your shopper admin panel with custom components features,
    | Here you can specify custom Feature for each feature available on Shopper.
    |
    | Each feature has its own components, routes and views. Once you have disabled it,
    | its menu and all its views will no longer be accessible on the admin panel.
    |
    */

    'brand' => FeatureState::ENABLED,
    'category' => FeatureState::ENABLED,
    'customer' => FeatureState::ENABLED,
    'collection' => FeatureState::ENABLED,
    'discount' => FeatureState::ENABLED,
    'order' => FeatureState::ENABLED,
    'product' => FeatureState::ENABLED,
    'review' => FeatureState::ENABLED,

];
