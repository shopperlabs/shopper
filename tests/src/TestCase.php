<?php

declare(strict_types=1);

namespace Shopper\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use Mckenziearts\BladeUntitledUIIcons\BladeUntitledUIIconsServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Shopper\Core\CoreServiceProvider;
use Shopper\Core\Database\Seeders\ShopperSeeder;
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
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeUntitledUIIconsServiceProvider::class,
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
            WizardServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('view.paths', [
            ...$app['config']->get('view.paths'),
            __DIR__ . '/../../packages/admin/resources/views',
        ]);
    }

    protected function asAdmin(): TestCase
    {
        return $this->actingAs($this->makeAdminUser(), config('shopper.auth.guard'));
    }

    protected function makeAdminUser(): User
    {
        $admin = User::factory()->create();

        $admin->assignRole(config('shopper.core.users.admin_role'));

        return $admin;
    }
}
