<?php

namespace Shopper\Framework\Providers;

use function define;
use function defined;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Framework\Console\UserCommand;
use Shopper\Framework\Console\InstallCommand;
use Shopper\Framework\Console\PublishCommand;
use Shopper\Framework\Console\SymlinkCommand;

class ShopperServiceProvider extends ServiceProvider
{
    /**
     * Shopper config files.
     *
     * @var array<string>
     */
    protected array $configFiles = [
        'system', 'routes', 'auth', 'mails',
    ];

    /**
     * Bootstrap any package services.
     */
    public function boot()
    {
        $this->registerPublishing();
        $this->registerDatabase();
        $this->registerResources();

        $this->app->register(RouteServiceProvider::class);

        Builder::macro('search', fn ($field, $string) => $string ? $this->where($field, 'like', '%' . $string . '%') : $this);
    }

    /**
     * Register the package's publishable resources.
     */
    public function registerPublishing()
    {
        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom(SHOPPER_PATH . "/config/{$config}.php", "shopper.{$config}");
            $this->publishes([SHOPPER_PATH . "/config/{$config}.php" => config_path("shopper/{$config}.php")], 'shopper-config');
        });

        $this->publishes([
            SHOPPER_PATH . '/resources/lang' => resource_path('lang/vendor/shopper'),
        ], 'shopper-lang');
    }

    /**
     * Register Database and seeders.
     */
    public function registerDatabase()
    {
        $this->loadMigrationsFrom(SHOPPER_PATH . '/database/migrations');
        $this->publishes([
            SHOPPER_PATH . '/database/seeders' => database_path('seeders'),
        ], 'shopper-seeders');
    }

    /**
     * Register provider.
     */
    public function registerProviders(): void
    {
        foreach ($this->provides() as $provide) {
            $this->app->register($provide);
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->registerProviders();

        if (! defined('SHOPPER_PATH')) {
            define('SHOPPER_PATH', realpath(__DIR__ . '/../../'));
        }

        $this->commands([
            InstallCommand::class,
            PublishCommand::class,
            SymlinkCommand::class,
            UserCommand::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            ComponentServiceProvider::class,
            EventServiceProvider::class,
            ModelServiceProvider::class,
            SidebarServiceProvider::class,
            ShopperSidebarServiceProvider::class,
        ];
    }

    /**
     * Register the package resources such as routes, templates, etc.
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(SHOPPER_PATH . '/resources/views', 'shopper');
        $this->loadTranslationsFrom(SHOPPER_PATH . '/resources/lang', 'shopper');
        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/shopper'));
    }
}
