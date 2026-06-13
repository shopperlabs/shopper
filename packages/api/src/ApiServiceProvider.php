<?php

declare(strict_types=1);

namespace Shopper\Api;

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
    }

    public function packageBooted(): void
    {
        $this->publishes(
            [__DIR__.'/../config/api.php' => config_path('shopper/api.php')],
            'shopper-config',
        );
    }
}
