<?php

declare(strict_types=1);

namespace Shopper;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\Messages\MailMessage;
use Livewire\Livewire;
use Shopper\Core\Shopper;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;
use Shopper\Http\Livewire\Pages;
use Shopper\Http\Middleware\Authenticate;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ShopperServiceProvider extends PackageServiceProvider
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

    protected string $root = __DIR__ . '/..';

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

        ResetPassword::toMailUsing(
            fn ($notifiable, string $token) => (new MailMessage())
                ->view('shopper::mails.email')
                ->line(__('shopper::pages/auth.email.mail.content'))
                ->action(__('shopper::pages/auth.email.mail.action'), url(config('app.url') . route('shopper.password.reset', $token, false)))
                ->line(__('shopper::pages/auth.email.mail.message'))
        );

        Builder::macro(
            'search',
            fn (string $field, mixed $string): Builder => $string
                ? $this->where($field, 'like', '%' . $string . '%') // @phpstan-ignore-line
                : $this
        );
    }

    public function packageRegistered(): void
    {
        $this->registerConfigFiles();
        $this->registerDatabase();

        $this->loadViewsFrom($this->root . '/resources/views', 'shopper');

        $this->app->register(SidebarServiceProvider::class);
        $this->app->scoped('shopper', fn () => new Shopper());
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
}
