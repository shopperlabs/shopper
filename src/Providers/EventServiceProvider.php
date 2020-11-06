<?php

namespace Shopper\Framework\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Shopper\Framework\Events\ProductCreated;
use Shopper\Framework\Events\StoreCreated;
use Shopper\Framework\Listeners\ChannelSubscriber;
use Shopper\Framework\Listeners\CreateProductSubscriber;
use Shopper\Framework\Listeners\InventorySubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        StoreCreated::class => [
            ChannelSubscriber::class,
            InventorySubscriber::class
        ],

        ProductCreated::class => [
            CreateProductSubscriber::class,
        ],
    ];
}
