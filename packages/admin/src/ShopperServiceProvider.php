<?php

declare(strict_types=1);

namespace Shopper;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\Messages\MailMessage;
use Livewire\Livewire;
use Shopper\Console;
use Shopper\Core\Events\BuildingSidebar;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;
use Shopper\Events\DashboardSidebar;
use Shopper\Events\OrderSidebar;
use Shopper\Events\ShopSidebar;
use Shopper\Http\Composers\GlobalComposer;
use Shopper\Http\Composers\SidebarCreator;
use Shopper\Http\Livewire\Pages;
use Shopper\Http\Middleware\Authenticate;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ShopperServiceProvider extends PackageServiceProvider
{
    use HasRegisterConfigAndMigrationFiles;

    protected array $configFiles = [
        'admin',
        'auth',
        'components',
        'models',
        'routes',
        'settings',
    ];

    protected string $root = __DIR__.'/..';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('shopper')
            ->hasTranslations()
            ->hasViewComponents('shopper')
            ->hasRoute('web')
            ->hasCommands([
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

        ResetPassword::toMailUsing(fn ($notifiable, string $token) =>
            (new MailMessage())
                ->view('shopper::mails.email')
                ->line(__('shopper::pages/auth.email.mail.content'))
                ->action(__('shopper::pages/auth.email.mail.action'), url(config('app.url') . route('shopper.password.reset', $token, false)))
                ->line(__('shopper::pages/auth.email.mail.message'))
        );

        Builder::macro(
            'search',
            fn ($field, $string) => $string
                ? $this->where($field, 'like', '%' . $string . '%')
                : $this
        );
    }

    public function packageRegistered(): void
    {
        $this->registerConfigFiles();
        $this->registerDatabase();
        $this->registerViews();

        $this->app['events']->listen(BuildingSidebar::class, DashboardSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, ShopSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, OrderSidebar::class);
    }

    public function bootModelRelationName(): void
    {
        Relation::morphMap([
            'brand' => config('shopper.models.brand'),
            'category' => config('shopper.models.category'),
            'collection' => config('shopper.models.collection'),
            'product' => config('shopper.models.product'),
            'channel' => config('shopper.models.channel'),
        ]);
    }

    public function bootLivewireComponents(): void
    {
        $prefix = config('shopper.components.prefix', 'shopper');

        Livewire::addPersistentMiddleware([Authenticate::class]);

        foreach (array_merge(config('shopper.components', []), $this->getLivewirePagesComponents()) as $alias => $component) {
            $alias = $prefix ? "$prefix-$alias" : $alias;

            Livewire::component($alias, $component);
        }
    }

    public function getLivewirePagesComponents(): array
    {
        return [
            'auth.login' => Pages\Auth\Login::class,
            'auth.password' => Pages\Auth\ForgotPassword::class,
            'auth.password-reset' => Pages\Auth\ResetPassword::class,
            'initialize' => Pages\Initialization::class,
        ];
    }

    public function registerViews(): void
    {
        $this->loadViewsFrom($this->root.'/resources/views', 'shopper');

        view()->composer('*', GlobalComposer::class);
        view()->creator('shopper::components.layouts.app.sidebar.secondary', SidebarCreator::class);
    }
}
