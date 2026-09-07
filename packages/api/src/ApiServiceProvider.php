<?php

declare(strict_types=1);

namespace Shopper\Api;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Shopper\Cart\Models\Contracts\Cart;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Currency;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('shopper-api')
            ->hasTranslations()
            ->hasRoutes(['store'])
            ->hasCommands([
                Console\InstallCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api.php', 'shopper.api');

        $this->app->singleton(Support\ResourceManifest::class);
    }

    public function packageBooted(): void
    {
        $this->publishes(
            [__DIR__.'/../config/api.php' => config_path('shopper/api.php')],
            'shopper-config',
        );

        $this->registerCurrencyCacheInvalidation();
        $this->registerRelationshipIncludeNames();
    }

    private function registerRelationshipIncludeNames(): void
    {
        $cart = resolve(Cart::class);
        $cart::resolveRelationUsing('payment_method', fn (Cart $cart): BelongsTo => $cart->paymentMethod());

        $order = resolve(Order::class);
        $order::resolveRelationUsing('payment_method', fn (Order $order): BelongsTo => $order->paymentMethod());
        $order::resolveRelationUsing('shipping_address', fn (Order $order): BelongsTo => $order->shippingAddress());
        $order::resolveRelationUsing('billing_address', fn (Order $order): BelongsTo => $order->billingAddress());
    }

    private function registerCurrencyCacheInvalidation(): void
    {
        $forget = static function (Currency $currency): void {
            Cache::forget('shopper.api.currency.'.$currency->code);

            if ($currency->wasChanged('code')) {
                Cache::forget('shopper.api.currency.'.$currency->getOriginal('code'));
            }
        };

        Currency::saved($forget);
        Currency::deleted($forget);
    }
}
