<?php

declare(strict_types=1);

namespace Shopper\Core;

use Carbon\Carbon;
use Shopper\Core\Console\InstallCommand;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class CoreServiceProvider extends PackageServiceProvider
{
    use HasRegisterConfigAndMigrationFiles;

    protected array $configFiles = [
        'core',
        'media',
        'models',
        'orders',
    ];

    protected string $root = __DIR__ . '/..';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('shopper-core')
            ->hasTranslations()
            ->hasCommands([
                InstallCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        setlocale(LC_ALL, config('app.locale'));

        Carbon::setLocale(config('app.locale'));
    }

    public function packageRegistered(): void
    {
        $this->registerConfigFiles();
        $this->registerDatabase();
    }
}
