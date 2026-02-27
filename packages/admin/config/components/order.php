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
        'order-index' => Livewire\Pages\Order\Index::class,
        'order-detail' => Livewire\Pages\Order\Detail::class,
        'order-shipments' => Livewire\Pages\Order\Shipments::class,
        'order-abandoned-carts' => Livewire\Pages\Order\AbandonedCarts::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'order-customer' => Livewire\Components\Orders\OrderCustomer::class,
        'order-fulfillment' => Livewire\Components\Orders\Fulfillment::class,
        'order-items' => Livewire\Components\Orders\OrderItems::class,
        'order-notes' => Livewire\Components\Orders\OrderNotes::class,
        'order-shipment-timeline' => Livewire\Components\Orders\ShipmentTimeline::class,
        'order-summary' => Livewire\Components\Orders\OrderSummary::class,

        'slide-overs.create-shipping-label' => Livewire\SlideOvers\CreateShippingLabel::class,
        'slide-overs.shipment-detail' => Livewire\SlideOvers\ShipmentDetail::class,
        'slide-overs.abandoned-cart-detail' => Livewire\SlideOvers\AbandonedCartDetail::class,
    ],

];
