<?php

declare(strict_types=1);

namespace Shopper\Framework\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;
use Livewire\Livewire;
use Shopper\Framework\Console;
use Shopper\Framework\Models\Shop\Channel;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ShopperServiceProvider extends PackageServiceProvider
{
    /**
     * Shopper config files.
     *
     * @var array<string>
     */
    protected array $configFiles = [
        'auth',
        'components',
        'mails',
        'routes',
        'system',
        'settings',
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('shopper')
            ->hasCommands($this->getCommands())
            ->hasViewComponents(config('shopper.components.prefix', 'shopper'));
    }

    public function getCommands(): array
    {
        return [
            Console\InstallCommand::class,
            Console\PublishCommand::class,
            Console\SymlinkCommand::class,
            Console\UserCommand::class,
        ];
    }

    public function packageBooted(): void
    {
        $this->bootLivewireComponents();
        $this->bootModelRelationName();

        Builder::macro(
            'search',
            fn ($field, $string) => $string
                ? $this->where($field, 'like', '%' . $string . '%')
                : $this
        );
    }

    public function packageRegistered(): void
    {
        foreach ($this->provides() as $provide) {
            $this->app->register($provide);
        }

        $this->registerDatabase();
        $this->registerConfigFiles();
        $this->registerViews();
    }

    public function bootModelRelationName(): void
    {
        Relation::morphMap([
            'brand' => config('shopper.system.models.brand'),
            'category' => config('shopper.system.models.category'),
            'collection' => config('shopper.system.models.collection'),
            'product' => config('shopper.system.models.product'),
            'channel' => Channel::class,
        ]);
    }

    public function bootLivewireComponents(): void
    {
        $prefix = config('shopper.components.prefix', 'shopper');

        Livewire::listen('component.hydrate', function ($component) {
            $this->app->singleton(Component::class, fn () => $component);
        });

        foreach (config('shopper.components.livewire', []) as $alias => $component) {
            $alias = $prefix ? "$prefix-$alias" : $alias;

            Livewire::component($alias, $component);
        }
    }

    public function registerConfigFiles(): void
    {
        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom(SHOPPER_PATH . "/config/{$config}.php", "shopper.{$config}");
            $this->publishes([SHOPPER_PATH . "/config/{$config}.php" => config_path("shopper/{$config}.php")], 'shopper-config');
        });
    }

    public function registerDatabase(): void
    {
        $this->loadMigrationsFrom(SHOPPER_PATH . '/database/migrations');
        $this->publishes([SHOPPER_PATH . '/database/seeders' => database_path('seeders')], 'shopper-seeders');
    }

    public function registeringPackage(): void
    {
        if (! defined('SHOPPER_PATH')) {
            define('SHOPPER_PATH', realpath(__DIR__ . '/../../'));
        }
    }

    public function provides(): array
    {
        return [
            EventServiceProvider::class,
            RouteServiceProvider::class,
            SidebarServiceProvider::class,
            ShopperSidebarServiceProvider::class,
        ];
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(SHOPPER_PATH . '/resources/views', 'shopper');

        $langPath = 'vendor/shopper';

        $langPath = (function_exists('lang_path'))
            ? lang_path($langPath)
            : resource_path('lang/' . $langPath);

        $this->loadTranslationsFrom(SHOPPER_PATH . '/resources/lang', 'shopper');
        $this->loadJsonTranslationsFrom($langPath);

        $this->publishes([SHOPPER_PATH . '/resources/lang' => $langPath], 'shopper-lang');
    }
}
