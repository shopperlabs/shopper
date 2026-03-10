<?php

declare(strict_types=1);

namespace Tests\Core;

use Shopper\Core\CoreServiceProvider;
use Shopper\Payment\PaymentServiceProvider;
use Shopper\ShopperServiceProvider;
use Shopper\Sidebar\SidebarServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\PermissionServiceProvider;
use Tests\Database\Seeders\TestSeeder;

abstract class TestCase extends \Tests\TestCase
{
    protected bool $seed = true;

    protected string $seeder = TestSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->freezeTime();
    }

    protected function getPackageProviders($app): array
    {
        return [
            CoreServiceProvider::class,
            ShopperServiceProvider::class,
            SidebarServiceProvider::class,
            MediaLibraryServiceProvider::class,
            PaymentServiceProvider::class,
            PermissionServiceProvider::class,
        ];
    }
}
