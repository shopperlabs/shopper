<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Shopper\Core\Console\Thanks;
use Shopper\Core\CoreServiceProvider;
use Shopper\Core\Database\Seeders\ShopperSeeder;
use Shopper\Database\Seeders\AuthTableSeeder;
use Shopper\ShopperServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\ProgressBar;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;

#[AsCommand(name: 'shopper:install')]
final class InstallCommand extends Command
{
    protected ProgressBar $progressBar;

    protected $signature = 'shopper:install';

    protected $description = 'Install Shopper e-commerce admin panel';

    public function __construct()
    {
        parent::__construct();

        if (file_exists(config_path('shopper/admin.php'))) {
            $this->setHidden();
        }
    }

    public function handle(): void
    {
        $this->progressBar = $this->output->createProgressBar(4);

        $this->introMessage();

        sleep(1);

        if (! $this->progressBar->getProgress()) {
            $this->progressBar->start();
        }

        $this->newLine();
        $this->components->info('Publishing configuration and migrations...');

        $this->call('vendor:publish', ['--provider' => CoreServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => ShopperServiceProvider::class]);
        $this->call(
            'vendor:publish',
            ['--provider' => MediaLibraryServiceProvider::class, '--tag' => 'medialibrary-migrations']
        );

        $this->progressBar->advance();

        $this->components->info('Publishing Filament assets...');
        $this->call('filament:assets');

        $this->components->info('Enabling Shopper symlink for storage...');
        $this->call('shopper:link');

        $this->progressBar->advance();

        if (confirm('Run database migrations and seeders?')) {
            $this->setupDatabase();
        }

        $this->completeSetup();

        if (! $this->option('no-interaction')) {
            (new Thanks($this->output))();
        }
    }

    protected function setupDatabase(): void
    {
        $this->components->info('Migrating the database tables into your application...');
        $this->call('migrate');

        $this->progressBar->advance();

        $this->components->info('Seeding domain data...');
        $this->call('db:seed', ['--class' => ShopperSeeder::class]);

        $this->components->info('Seeding roles and permissions...');
        $this->call('db:seed', ['--class' => AuthTableSeeder::class]);

        $this->progressBar->advance();

        usleep(350000);

        $this->progressBar->finish();
    }

    protected function completeSetup(): void
    {
        $this->progressBar->finish();

        $this->components->info("
                                      ,@@@@@@@,
                              ,,,.   ,@@@@@@/@@,  .oo8888o.
                           ,&%%&%&&%,@@@@@/@@@@@@,8888\\88/8o
                          ,%&\\%&&%&&%,@@@\\@@@/@@@88\\88888/88'
                          %&&%&%&/%&&%@@\\@@/ /@@@88888\\88888'
                          %&&%/ %&%%&&@@\\ V /@@' `88\\8 `/88'
                          `&%\\ ` /%&'    |.|        \\ '|8'
                              |o|        | |         | |
                              |.|        | |         | |
       ======================== Installation Complete 🚀 ======================
        ");

        $this->comment('Before creating an admin user, add the InteractsWithShopper trait to your User model.');
        $this->comment("To create a user, run 'php artisan shopper:user'");
    }

    protected function introMessage(): void
    {
        note($this->shopperLogo());
        intro('✦ Shopper :: Install ✦');
    }

    private function shopperLogo(): string
    {
        return
            <<<'HEADER'
            ███████╗ ██╗  ██╗  ██████╗  ██████╗  ██████╗  ███████╗ ██████╗
            ██╔════╝ ██║  ██║ ██╔═══██╗ ██╔══██╗ ██╔══██╗ ██╔════╝ ██╔══██╗
            ███████╗ ███████║ ██║   ██║ ██████╔╝ ██████╔╝ █████╗   ██████╔╝
            ╚════██║ ██╔══██║ ██║   ██║ ██╔═══╝  ██╔═══╝  ██╔══╝   ██╔══██╗
            ███████║ ██║  ██║ ╚██████╔╝ ██║      ██║      ███████╗ ██║  ██║
            ╚══════╝ ╚═╝  ╚═╝  ╚═════╝  ╚═╝      ╚═╝      ╚══════╝ ╚═╝  ╚═╝
            HEADER;
    }
}
