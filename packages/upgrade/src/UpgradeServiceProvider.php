<?php

declare(strict_types=1);

namespace Shopper\Upgrade;

use Shopper\Upgrade\Console\FixZeroDecimalCurrency;
use Shopper\Upgrade\Console\MigratePermissions;
use Shopper\Upgrade\Console\RunRector;
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
                MigratePermissions::class,
                RunRector::class,
            ]);
    }
}
