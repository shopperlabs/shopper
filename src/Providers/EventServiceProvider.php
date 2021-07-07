<?php

namespace Shopper\Framework\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Shopper\Framework\Events\Products\ProductCreated;
use Shopper\Framework\Events\Products\ProductRemoved;
use Shopper\Framework\Events\Products\ProductUpdated;
use Shopper\Framework\Listeners\Products\CreateProductSubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ProductCreated::class => [
            CreateProductSubscriber::class,
        ],

        ProductUpdated::class => [],

        ProductRemoved::class => [],
    ];
}
