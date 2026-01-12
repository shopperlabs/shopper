<?php

declare(strict_types=1);

namespace Shopper\Core;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Shopper\Core\Console\InstallCommand;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class CoreServiceProvider extends PackageServiceProvider
{
    use HasRegisterConfigAndMigrationFiles;

    /** @var string[] */
    protected array $configFiles = [
        'core',
        'media',
        'models',
        'orders',
    ];

    protected string $root = __DIR__.'/..';

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

        $this->bootModelRelationName();
    }

    public function packageRegistered(): void
    {
        $this->registerConfigFiles();
        $this->registerDatabase();
        $this->registerModelBindings();
    }

    protected function registerModelBindings(): void
    {
        $models = [
            'brand' => Models\Contracts\Brand::class,
            'category' => Models\Contracts\Category::class,
            'collection' => Models\Contracts\Collection::class,
            'product' => Models\Contracts\Product::class,
            'variant' => Models\Contracts\ProductVariant::class,
            'channel' => Models\Contracts\Channel::class,
        ];

        foreach ($models as $configKey => $contract) {
            $this->app->bind($contract, config("shopper.models.{$configKey}"));
        }
    }

    protected function bootModelRelationName(): void
    {
        Relation::morphMap([
            'brand' => config('shopper.models.brand'),
            'category' => config('shopper.models.category'),
            'collection' => config('shopper.models.collection'),
            'product' => config('shopper.models.product'),
            'variant' => config('shopper.models.variant'),
            'channel' => config('shopper.models.channel'),
        ]);
    }
}
