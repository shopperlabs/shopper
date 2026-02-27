<?php

declare(strict_types=1);

namespace Shopper;

use Closure;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;
use Shopper\Concerns\TwoFactorAuthenticationProvider;
use Shopper\Contracts\LoginResponse as LoginResponseContract;
use Shopper\Contracts\TwoFactorAuthenticationProvider as TwoFactorAuthenticationProviderContract;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;
use Shopper\Facades\Shopper;
use Shopper\Http\Middleware\Authenticate;
use Shopper\Http\Middleware\DispatchShopper;
use Shopper\Http\Responses\LoginResponse;
use Shopper\Livewire\Components;
use Shopper\Livewire\Pages;
use Shopper\Providers\FeatureServiceProvider;
use Shopper\Providers\SidebarServiceProvider;
use Shopper\Settings\SettingManager;
use Shopper\Traits\LoadComponents;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ShopperServiceProvider extends PackageServiceProvider
{
    use HasRegisterConfigAndMigrationFiles;
    use LoadComponents;

    /** @var string[] */
    protected array $configFiles = [
        'addons',
        'admin',
        'auth',
        'features',
        'routes',
        'settings',
    ];

    /** @var string[] */
    protected array $componentsConfig = ['account', 'dashboard', 'setting'];

    protected string $root = __DIR__.'/..';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('shopper')
            ->hasTranslations()
            ->hasViewComponents('shopper')
            ->hasRoute('web')
            ->hasCommands([
                Console\ComponentPublishCommand::class,
                Console\InstallCommand::class,
                Console\PublishCommand::class,
                Console\MakePageCommand::class,
                Console\MakeShopperPageCommand::class,
                Console\SymlinkCommand::class,
                Console\UserCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        $this->bootLivewireComponents();
        $this->bootAddons();
        $this->registerCustomFilamentItems();

        Shopper::serving(function (): void {
            Shopper::setServingStatus();
        });
    }

    public function packageRegistered(): void
    {
        $this->registerConfigFiles();
        $this->registerComponentsConfigFiles();

        $this->app->singleton(
            TwoFactorAuthenticationProviderContract::class,
            fn ($app): TwoFactorAuthenticationProvider => new TwoFactorAuthenticationProvider($app->make(Google2FA::class))
        );

        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
        $this->app->register(SidebarServiceProvider::class);
        $this->app->register(FeatureServiceProvider::class);
        $this->app->scoped('shopper', fn (): ShopperPanel => new ShopperPanel);
        $this->app->singleton(SettingManager::class, fn (): SettingManager => (new SettingManager)->register(
            config('shopper.settings.items', [])
        ));

        $this->loadViewsFrom($this->root.'/resources/views', 'shopper');
    }

    protected function bootAddons(): void
    {
        $panel = app('shopper');
        $manager = $panel->addonManager();

        $manager->boot($panel);

        foreach ($manager->getSidebars() as $sidebarClass) {
            $this->app['events']->listen(Sidebar\SidebarBuilder::class, $sidebarClass);
        }

        foreach ($manager->getLivewireComponents() as $alias => $component) {
            Livewire::component("shopper-{$alias}", $component);
        }

        foreach ($manager->getViewNamespaces() as $namespace => $path) {
            $this->loadViewsFrom($path, $namespace);
        }

        $settingItems = $manager->getSettingItems();

        if ($settingItems !== []) {
            app(SettingManager::class)->register($settingItems);
        }
    }

    protected function bootLivewireComponents(): void
    {
        Livewire::addPersistentMiddleware([
            Authenticate::class,
            DispatchShopper::class,
        ]);

        foreach (array_merge(
            $this->getLivewireComponents(),
            $this->loadLivewireComponents('account'),
            $this->loadLivewireComponents('dashboard'),
            $this->loadLivewireComponents('setting'),
        ) as $alias => $component) {
            Livewire::component("shopper-{$alias}", $component);
        }
    }

    /**
     * @return string[]
     */
    protected function getLivewireComponents(): array
    {
        return [
            'auth.login' => Pages\Auth\Login::class,
            'auth.password' => Pages\Auth\ForgotPassword::class,
            'auth.password-reset' => Pages\Auth\ResetPassword::class,
            'setup-guide' => Components\Dashboard\SetupGuide::class,
            'dashboard.stat-cards' => Components\Dashboard\StatCards::class,
            'dashboard.revenue-chart' => Components\Dashboard\RevenueChart::class,
            'dashboard.recent-orders' => Components\Dashboard\RecentOrders::class,
            'dashboard.top-selling-products' => Components\Dashboard\TopSellingProducts::class,
            'initialize' => Pages\Initialization::class,
            'initialize-wizard' => Components\Initialization\InitializationWizard::class,
            'initialize-store-information' => Components\Initialization\Steps\StoreInformation::class,
            'initialize-store-address' => Components\Initialization\Steps\StoreAddress::class,
            'initialize-store-social-link' => Components\Initialization\Steps\StoreSocialLink::class,
        ];
    }

    protected function registerCustomFilamentItems(): void
    {
        FilamentColor::register([
            'primary' => config('shopper.admin.filament_color'),
            'teal' => Color::Teal,
            'green' => Color::Green,
            'sky' => Color::Sky,
            'indigo' => Color::Indigo,
            'info' => Color::Cyan,
        ]);

        Field::configureUsing(
            fn (Field $field): Field => $field
                ->uniqueValidationIgnoresRecordByDefault(false)
        );

        TextColumn::macro('currency', function (string|Closure|null $currency = null): TextColumn {
            /*** @var TextColumn $this */
            // @phpstan-ignore-next-line
            $this->formatStateUsing(static function (Column $column, ?int $state) use ($currency): ?string {
                if (blank($state)) {
                    return null;
                }

                if (blank($currency)) {
                    $currency = shopper_currency();
                }

                return shopper_money_format(
                    amount: $state,
                    currency: mb_strtoupper($column->evaluate($currency)),
                );
            });

            return $this; // @phpstan-ignore-line
        });

        TextInput::macro('currencyMask', function ($thousandSeparator = ',', $decimalSeparator = '.', $precision = 2): TextInput {
            $this->view = 'shopper::components.filament.forms.currency-mask'; // @phpstan-ignore-line
            $this->viewData(compact('thousandSeparator', 'decimalSeparator', 'precision')); // @phpstan-ignore-line

            return $this; // @phpstan-ignore-line
        });

        SpatieMediaLibraryFileUpload::configureUsing(
            fn (SpatieMediaLibraryFileUpload $spatieFileUpload): SpatieMediaLibraryFileUpload => $spatieFileUpload
                ->visibility('public')
        );

        FileUpload::configureUsing(
            fn (FileUpload $fileUpload): FileUpload => $fileUpload->visibility('public')
        );

        ImageColumn::configureUsing(
            fn (ImageColumn $imageColumn): ImageColumn => $imageColumn->visibility('public')
        );

        ImageEntry::configureUsing(
            fn (ImageEntry $imageEntry): ImageEntry => $imageEntry->visibility('public')
        );
    }
}
