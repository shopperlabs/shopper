<?php

declare(strict_types=1);

namespace Shopper;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Shopper\Core\Events\Orders\OrderCreated;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Listeners\Notifications\SendNewOrderNotification;
use Shopper\Listeners\Notifications\SendOrderPaidNotification;
use Shopper\Listeners\Notifications\SendPaymentFailedNotification;
use Shopper\Payment\Events\PaymentFailed;

final class EventServiceProvider extends ServiceProvider
{
    /** @var array<class-string, array<int, class-string>> */
    protected $listen = [
        OrderCreated::class => [
            SendNewOrderNotification::class,
        ],
        OrderPaid::class => [
            SendOrderPaidNotification::class,
        ],
        PaymentFailed::class => [
            SendPaymentFailedNotification::class,
        ],
    ];
}
