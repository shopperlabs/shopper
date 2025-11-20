<?php

declare(strict_types=1);

namespace Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Codeat3\BladePhosphorIcons\BladePhosphorIconsServiceProvider;
use CodeWithDennis\FilamentSelectTree\FilamentSelectTreeServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JaOcero\RadioDeck\RadioDeckServiceProvider;
use Livewire\LivewireServiceProvider;
use Mckenziearts\BladeUntitledUIIcons\BladeUntitledUIIconsServiceProvider;
use Milon\Barcode\BarcodeServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use PDO;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Shopper\Core\CoreServiceProvider;
use Shopper\Core\Database\Seeders\ShopperSeeder;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;
use Shopper\Core\Models\User;
use Shopper\ShopperServiceProvider;
use Shopper\Sidebar\SidebarServiceProvider;
use Spatie\LivewireWizard\WizardServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use TailwindMerge\Laravel\TailwindMergeServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use WithWorkbench;

    protected bool $seed = true;

    protected string $seeder = ShopperSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        // Freeze time to avoid timestamp errors
        $this->freezeTime();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ActionsServiceProvider::class,
            BarcodeServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeUntitledUIIconsServiceProvider::class,
            BladePhosphorIconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            CoreServiceProvider::class,
            PermissionServiceProvider::class,
            LivewireServiceProvider::class,
            ShopperServiceProvider::class,
            SidebarServiceProvider::class,
            FormsServiceProvider::class,
            SupportServiceProvider::class,
            NotificationsServiceProvider::class,
            TablesServiceProvider::class,
            MediaLibraryServiceProvider::class,
            TailwindMergeServiceProvider::class,
            RadioDeckServiceProvider::class,
            FilamentSelectTreeServiceProvider::class,
            WizardServiceProvider::class,
        ];
    }

    protected function asAdmin(): self
    {
        return $this->actingAs($this->makeAdminUser(), config('shopper.auth.guard'));
    }

    protected function makeAdminUser(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole(config('shopper.core.roles.admin'));

        return $admin;
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('view.paths', [
            ...$app['config']->get('view.paths'),
            __DIR__.'/../packages/admin/resources/views',
        ]);

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', ':memory:'),
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        $app['config']->set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('MYSQL_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_DATABASE', 'testing'),
            'username' => env('MYSQL_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('MYSQL_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'timezone' => '+00:00',
            'options' => [
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
        ]);

        $app['config']->set('database.connections.pgsql', [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('PGSQL_PORT', env('DB_PORT', '5432')),
            'database' => env('DB_DATABASE', 'testing'),
            'username' => env('PGSQL_USERNAME', env('DB_USERNAME', 'postgres')),
            'password' => env('PGSQL_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
            'options' => [
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
        ]);

        $app['config']->set('database.default', env('DB_CONNECTION', 'testing'));
        $app['config']->set('database.connections.testing', $app['config']->get('database.connections.sqlite'));
    }
}
