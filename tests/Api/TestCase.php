<?php

declare(strict_types=1);

namespace Tests\Api;

use Laravel\Sanctum\SanctumServiceProvider;
use Livewire\LivewireServiceProvider;
use Shopper\Api\ApiServiceProvider;
use Shopper\Cart\CartServiceProvider;
use Shopper\Core\CoreServiceProvider;
use Shopper\Http\HttpServiceProvider;
use Shopper\Payment\PaymentServiceProvider;
use Shopper\Shipping\ShippingServiceProvider;
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
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            CoreServiceProvider::class,
            ShopperServiceProvider::class,
            SidebarServiceProvider::class,
            CartServiceProvider::class,
            PaymentServiceProvider::class,
            ShippingServiceProvider::class,
            MediaLibraryServiceProvider::class,
            PermissionServiceProvider::class,
            SanctumServiceProvider::class,
            HttpServiceProvider::class,
            ApiServiceProvider::class,
        ];
    }
}
