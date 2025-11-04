<?php

declare(strict_types=1);

namespace Shopper\Core\Console;

use Illuminate\Console\Command;
use Shopper\Core\CoreServiceProvider;
use Shopper\Core\Database\Seeders\ShopperSeeder;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\ProgressBar;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;

#[AsCommand(name: 'shopper:install')]
final class InstallCommand extends Command
{
    protected $signature = 'shopper:install';

    protected $description = 'Install Shopper core base';

    protected ProgressBar $progressBar;

    public function __construct()
    {
        parent::__construct();

        if (file_exists(config_path('shopper/core.php'))) {
            $this->setHidden();
        }
    }

    public function handle(): void
    {
        $this->progressBar = $this->output->createProgressBar(3);

        $this->introMessage();

        sleep(1);

        if (! $this->progressBar->getProgress()) {
            $this->progressBar->start();
        }

        $this->newLine();
        $this->components->info('Publishing configuration...');

        $this->call('vendor:publish', ['--provider' => CoreServiceProvider::class]);
        $this->call(
            'vendor:publish',
            ['--provider' => MediaLibraryServiceProvider::class, '--tag' => 'medialibrary-migrations']
        );

        $this->progressBar->advance();

        if (confirm('Run database migrations and seeders ?')) {
            $this->setupDatabaseConfig();
        }

        if (! file_exists(config_path('shopper/admin.php'))) {
            $this->newLine();

            $this->line('Installing Shopper Admin Panel 🚧.');
            $this->call('shopper:panel-install');
        }
    }

    protected function setupDatabaseConfig(): void
    {
        $this->components->info('Migrating the database tables into your application 🔽');
        $this->call('migrate');

        $this->progressBar->advance();

        $this->components->info('Seed data into your database 🔽');
        $this->call('db:seed', ['--class' => ShopperSeeder::class]);

        $this->progressBar->advance();

        // Visually slow down the installation process so that the user can read what's happening
        usleep(350000);

        $this->progressBar->finish();
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

    protected function introMessage(): void
    {
        note($this->shopperLogo());
        intro('✦ Laravel Shopper :: Install ✦');
    }
}
