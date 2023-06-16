<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

trait HasRegisterConfigAndMigrationFiles
{
    public function registerConfigFiles(): void
    {
        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom($this->root . "/config/{$config}.php", "shopper.{$config}");
            $this->publishes([$this->root . "/config/{$config}.php" => config_path("shopper/{$config}.php")], 'shopper-config');
        });
    }

    public function registerDatabase(): void
    {
        if (! file_exists($this->root . '/database')) {
            return;
        }

        $this->loadMigrationsFrom($this->root . '/database/migrations');
        $this->publishes([$this->root . '/database/seeders' => database_path('seeders')], 'shopper-seeders');
    }
}
