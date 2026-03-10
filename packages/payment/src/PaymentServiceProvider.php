<?php

declare(strict_types=1);

namespace Shopper\Payment;

use Closure;
use Illuminate\Support\ServiceProvider;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;
use Shopper\Payment\Services\PaymentProcessingService;

final class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/payment.php', 'shopper.payment');

        $this->app->singleton(PaymentManager::class, fn (): PaymentManager => new PaymentManager);
        $this->app->singleton(PaymentProcessingService::class);

        $this->app->bind(
            'shopper.payment.logo',
            fn (): Closure => fn (PaymentMethod $method): ?string => Payment::driver($method->driver ?? 'manual')->logo()
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/payment.php' => config_path('shopper/payment.php'),
        ], 'shopper-config');

        Order::resolveRelationUsing('paymentTransactions', fn (Order $order) => $order->hasMany(PaymentTransaction::class, 'order_id'));
    }
}
