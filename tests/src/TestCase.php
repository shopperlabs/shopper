<?php

declare(strict_types=1);

namespace Shopper\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Shopper\Core\CoreServiceProvider;
use Shopper\Core\Models\User;
use Shopper\ShopperServiceProvider;
use Shopper\Sidebar\SidebarServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            CoreServiceProvider::class,
            ShopperServiceProvider::class,
            SidebarServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            MediaLibraryServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../packages/core/database/migrations');
    }
}
