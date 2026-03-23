<?php

declare(strict_types=1);

namespace Tests\Admin;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Codeat3\BladePhosphorIcons\BladePhosphorIconsServiceProvider;
use CodeWithDennis\FilamentSelectTree\FilamentSelectTreeServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\Livewire\Partials\DataStoreOverride;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use JaOcero\RadioDeck\RadioDeckServiceProvider;
use Laravelcm\LivewireSlideOvers\LivewireSlideOverServiceProvider;
use Livewire\LivewireServiceProvider;
use Livewire\Mechanisms\DataStore;
use Mckenziearts\BladeUntitledUIIcons\BladeUntitledUIIconsServiceProvider;
use Milon\Barcode\BarcodeServiceProvider;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Shopper\Cart\CartServiceProvider;
use Shopper\Core\CoreServiceProvider;
use Shopper\Payment\PaymentServiceProvider;
use Shopper\Shipping\ShippingServiceProvider;
use Shopper\ShopperServiceProvider;
use Shopper\Sidebar\SidebarServiceProvider;
use Shopper\Stripe\StripeServiceProvider;
use Spatie\LivewireWizard\WizardServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\PermissionServiceProvider;
use TailwindMerge\Laravel\TailwindMergeServiceProvider;
use Tests\Core\Stubs\User;
use Tests\Database\Seeders\TestSeeder;

abstract class TestCase extends \Tests\TestCase
{
    protected bool $seed = true;

    protected string $seeder = TestSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->fixFilamentDataStoreBinding();

        // Freeze time to avoid timestamp errors
        $this->freezeTime();
    }

    protected function fixFilamentDataStoreBinding(): void
    {
        $this->app->singleton(DataStore::class, DataStoreOverride::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            ActionsServiceProvider::class,
            BarcodeServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeUntitledUIIconsServiceProvider::class,
            BladePhosphorIconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            CartServiceProvider::class,
            CoreServiceProvider::class,
            FilamentServiceProvider::class,
            PaymentServiceProvider::class,
            PermissionServiceProvider::class,
            ShippingServiceProvider::class,
            ShopperServiceProvider::class,
            SidebarServiceProvider::class,
            StripeServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            SchemasServiceProvider::class,
            SupportServiceProvider::class,
            NotificationsServiceProvider::class,
            TablesServiceProvider::class,
            MediaLibraryServiceProvider::class,
            TailwindMergeServiceProvider::class,
            RadioDeckServiceProvider::class,
            FilamentSelectTreeServiceProvider::class,
            LivewireSlideOverServiceProvider::class,
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
        $admin->assignRole(config('shopper.admin.roles.admin'));

        return $admin;
    }
}
