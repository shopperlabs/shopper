<?php

declare(strict_types=1);

namespace Shopper;

use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;
use Shopper\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;
use Shopper\Contracts\LoginResponse as LoginResponseContract;
use Shopper\Contracts\TwoFactorAuthenticationProvider as TwoFactorAuthenticationProviderContract;
use Shopper\Contracts\TwoFactorDisabledResponse as TwoFactorDisabledResponseContract;
use Shopper\Contracts\TwoFactorEnabledResponse as TwoFactorEnabledResponseContract;
use Shopper\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;
use Shopper\Facades\Shopper;
use Shopper\Http\Middleware\Authenticate;
use Shopper\Http\Middleware\DispatchShopper;
use Shopper\Http\Responses\FailedTwoFactorLoginResponse;
use Shopper\Http\Responses\LoginResponse;
use Shopper\Http\Responses\TwoFactorDisabledResponse;
use Shopper\Http\Responses\TwoFactorEnabledResponse;
use Shopper\Http\Responses\TwoFactorLoginResponse;
use Shopper\Livewire\Components;
use Shopper\Livewire\Pages;
use Shopper\Providers\FeatureServiceProvider;
use Shopper\Providers\SidebarServiceProvider;
use Shopper\Providers\TwoFactorAuthenticationProvider;
use Shopper\Traits\LoadComponents;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ShopperServiceProvider extends PackageServiceProvider
{
    use HasRegisterConfigAndMigrationFiles;
    use LoadComponents;

    protected array $configFiles = [
        'admin',
        'auth',
        'features',
        'routes',
        'settings',
    ];

    protected array $componentsConfig = ['account', 'dashboard', 'setting'];

    protected string $root = __DIR__ . '/..';

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
                Console\SymlinkCommand::class,
                Console\UserCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        $this->bootLivewireComponents();

        $this->bootModelRelationName();

        Shopper::serving(function (): void {
            Shopper::setServingStatus();
        });
    }

    public function packageRegistered(): void
    {
        $this->registerConfigFiles();
        $this->registerComponentsConfigFiles();
        $this->registerResponseBindings();

        $this->app->singleton(
            TwoFactorAuthenticationProviderContract::class,
            fn ($app) => new TwoFactorAuthenticationProvider($app->make(Google2FA::class))
        );

        $this->app->bind(LoginResponseContract::class, LoginResponse::class);

        $this->app->register(SidebarServiceProvider::class);
        $this->app->register(FeatureServiceProvider::class);

        $this->app->scoped('shopper', fn (): ShopperPanel => new ShopperPanel());

        $this->loadViewsFrom($this->root . '/resources/views', 'shopper');
    }

    protected function bootModelRelationName(): void
    {
        Relation::morphMap([
            'brand' => config('shopper.models.brand'),
            'category' => config('shopper.models.category'),
            'collection' => config('shopper.models.collection'),
            'product' => config('shopper.models.product'),
            'channel' => config('shopper.models.channel'),
        ]);
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

    protected function getLivewireComponents(): array
    {
        return [
            'auth.login' => Pages\Auth\Login::class,
            'auth.password' => Pages\Auth\ForgotPassword::class,
            'auth.password-reset' => Pages\Auth\ResetPassword::class,
            'initialize' => Pages\Initialization::class,
            'initialize-wizard' => Components\Initialization\InitializationWizard::class,
            'initialize-store-information' => Components\Initialization\Steps\StoreInformation::class,
            'initialize-store-address' => Components\Initialization\Steps\StoreAddress::class,
            'initialize-store-social-link' => Components\Initialization\Steps\StoreSocialLink::class,
            'forms.trix' => Components\Forms\Trix::class,
            'forms.icon-picker' => Components\Forms\IconPicker::class,
            'forms.uploads.multiple' => Components\Forms\Uploads\Multiple::class,
            'forms.uploads.single' => Components\Forms\Uploads\Single::class,
            'products.attributes.multiple-choice' => Components\Products\Attributes\MultipleChoice::class,
            'products.attributes.single-choice' => Components\Products\Attributes\SingleChoice::class,
            'products.attributes.text' => Components\Products\Attributes\Text::class,
        ];
    }

    protected function registerResponseBindings(): void
    {
        $this->app->singleton(FailedTwoFactorLoginResponseContract::class, FailedTwoFactorLoginResponse::class);
        $this->app->singleton(TwoFactorDisabledResponseContract::class, TwoFactorDisabledResponse::class);
        $this->app->singleton(TwoFactorEnabledResponseContract::class, TwoFactorEnabledResponse::class);
        $this->app->singleton(TwoFactorLoginResponseContract::class, TwoFactorLoginResponse::class);
    }
}
