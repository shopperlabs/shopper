<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Shopper\Framework\Console\InstallCommand;
use Shopper\Framework\Console\PublishCommand;
use Shopper\Framework\Console\SymlinkCommand;
use Shopper\Framework\Console\UserCommand;

class ShopperServiceProvider extends ServiceProvider
{
    /**
     * Shopper config files.
     *
     * @var string[]
     */
    protected $configFiles = [
        'system', 'routes', 'auth',
    ];

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
        $this->registerDatabase();
        $this->registerResources();

        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    public function registerPublishing()
    {
        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom(SHOPPER_PATH . "/config/$config.php", "shopper.$config");
            $this->publishes([SHOPPER_PATH. "/config/$config.php" => config_path("shopper/$config.php")], 'shopper');
        });

        $this->publishes([
            SHOPPER_PATH . '/resources/lang' => resource_path('lang/vendor/shopper'),
        ], 'shopper-lang');
    }

    /**
     * Register Database and seeders.
     *
     * @return void
     */
    public function registerDatabase()
    {
        $this->loadMigrationsFrom(SHOPPER_PATH . '/database/migrations');
        $this->publishes([
            SHOPPER_PATH . '/database/seeds' => database_path('seeds'),
        ], 'shopper-seeders');
    }

    /**
     * Register the package resources such as routes, templates, etc.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(SHOPPER_PATH . '/resources/views', 'shopper');
        $this->loadTranslationsFrom(SHOPPER_PATH . '/resources/lang', 'shopper');
        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/shopper'));
    }

    /**
     * Register provider
     *
     * @return void
     */
    public function registerProviders() : void
    {
        foreach ($this->provides() as $provide) {
            $this->app->register($provide);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerProviders();

        if (!defined('SHOPPER_PATH')) {
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
     *
     * @return array
     */
    public function provides() : array
    {
        return [
            ShopperSidebarServiceProvider::class,
            SidebarServiceProvider::class,
            EventServiceProvider::class,
            ComponentServiceProvider::class,
        ];
    }
}
