<?php

namespace Shopper\Framework\Tests;

use Livewire\LivewireServiceProvider;
use Mockery;
use Orchestra\Testbench\TestCase;
use Shopper\Framework\FrameworkServiceProvider;
use Shopper\Framework\Models\User\User;

class ShopperTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['migrator']->path(__DIR__ . '/../database/migrations');

        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Use test User model for users provider
        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function getPackageProviders($app): array
    {
        return [LivewireServiceProvider::class, FrameworkServiceProvider::class];
    }
}
