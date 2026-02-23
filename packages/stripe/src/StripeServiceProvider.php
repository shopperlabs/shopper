<?php

declare(strict_types=1);

namespace Shopper\Stripe;

use Illuminate\Support\ServiceProvider;
use Shopper\Payment\Facades\Payment;

final class StripeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/stripe.php', 'shopper.stripe');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/stripe.php' => config_path('shopper/stripe.php'),
        ], 'shopper-stripe-config');

        Payment::extend('stripe', fn (): StripeDriver => new StripeDriver(
            secretKey: (string) config('shopper.stripe.secret_key'),
            publishableKey: (string) config('shopper.stripe.publishable_key'),
            webhookSecret: (string) config('shopper.stripe.webhook_secret'),
            captureMethod: (string) config('shopper.stripe.capture_method', 'manual'),
        ));
    }
}
