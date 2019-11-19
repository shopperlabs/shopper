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
        $this->publishes([
            __DIR__ . '/../../config/shopper.php' => config_path('shopper.php'),
        ], 'shopper-config');

        $this->publishes([
            __DIR__ . '/../../public' => public_path('shopper'),
        ], 'shopper-assets');

        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/shopper'),
        ], 'shopper-lang');
    }

    /**
     * Register Datababse and seeders
     *
     * @return void
     */
    public function registerDatabase()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->publishes([
            __DIR__ . '/../../database/seeds' => database_path('seeds'),
        ], 'shopper-seeders');
    }

    /**
     * Register the package resources such as routes, templates, etc.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'shopper');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'shopper');
        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/shopper'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            InstallCommand::class,
            PublishCommand::class,
            UserCommand::class,
            SymlinkCommand::class
        ]);
    }

    /**
     * Get the translation keys from file.
     *
     * @return array
     */
    private static function getTranslations()
    {
        $translationFile = resource_path('lang/' . app()->getLocale() . '.json');

        if (! is_readable($translationFile)) {
            $translationFile = resource_path('lang/' . app('translator')->getFallback() . '.json');
        }

        if (! is_readable($translationFile)) {
            return [];
        }

        return json_decode(file_get_contents($translationFile), true);
    }
}
