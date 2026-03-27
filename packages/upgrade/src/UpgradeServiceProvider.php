<?php

declare(strict_types=1);

namespace Shopper\Upgrade;

use Laravel\Mcp\Facades\Mcp;
use Shopper\Upgrade\Console\FixZeroDecimalCurrency;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class UpgradeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('shopper-upgrade')
            ->hasCommands([
                FixZeroDecimalCurrency::class,
            ]);
    }

    public function packageBooted(): void
    {
        Mcp::local('shopper-upgrade', ShopperUpgradeServer::class);
    }
}
