<?php

declare(strict_types=1);

namespace Shopper\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Shopper\Core\CoreServiceProvider;
use Shopper\ShopperServiceProvider;
use Shopper\Sidebar\SidebarServiceProvider;
use Shopper\Tests\Models\User;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            CoreServiceProvider::class,
            PermissionServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            ShopperServiceProvider::class,
            SidebarServiceProvider::class,
            LivewireServiceProvider::class,
            MediaLibraryServiceProvider::class,
            NotificationsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
    }
}
