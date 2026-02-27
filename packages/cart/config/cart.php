<?php

declare(strict_types=1);

use Shopper\Cart\Pipelines;

return [

    /*
    |--------------------------------------------------------------------------
    | Cart Calculation Pipelines
    |--------------------------------------------------------------------------
    |
    | The cart calculation is executed through a pipeline of steps. You can
    | add, remove, or reorder steps to customize the calculation flow.
    |
    */

    'pipelines' => [
        'cart' => [
            Pipelines\CalculateLines::class,
            Pipelines\ApplyDiscounts::class,
            Pipelines\CalculateTax::class,
            Pipelines\Calculate::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Configuration
    |--------------------------------------------------------------------------
    */

    'session' => [
        'key' => 'shopper_cart',
        'auto_create' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Abandoned Cart Threshold
    |--------------------------------------------------------------------------
    |
    | A cart is considered "abandoned" when it has had no activity for
    | at least this many minutes. Default is 60 minutes (1 hour).
    |
    */

    'abandoned_after_minutes' => 60,

    /*
    |--------------------------------------------------------------------------
    | Cart Pruning
    |--------------------------------------------------------------------------
    |
    | Abandoned carts older than this number of days will be deleted
    | when running the `shopper:prune-carts` command.
    |
    */

    'prune_after_days' => 30,

    /*
    |--------------------------------------------------------------------------
    | Cart Models
    |--------------------------------------------------------------------------
    |
    | These models can be overridden to allow full customization of the cart.
    |
    */

    'models' => [
        'cart' => Shopper\Cart\Models\Cart::class,
        'cart_line' => Shopper\Cart\Models\CartLine::class,
    ],

];
