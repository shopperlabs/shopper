<?php

declare(strict_types=1);

namespace Shopper\Api;

use Illuminate\Support\Facades\Cache;
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
