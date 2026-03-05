<?php

declare(strict_types=1);

namespace Shopper\Console;

use Closure;
use Illuminate\Console\Command;
use Shopper\Core\Database\Seeders\ShopperSeeder;
use Shopper\Database\Seeders\AuthTableSeeder;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\spin;

#[AsCommand(name: 'shopper:install')]
final class InstallCommand extends Command
{
    protected $signature = 'shopper:install';

    protected $description = 'Install the Shopper e-commerce admin panel';

    public function __construct()
    {
        parent::__construct();

        if (file_exists(config_path('shopper/admin.php'))) {
            $this->setHidden();
        }
    }

    public function handle(): void
    {
        $this->renderLogo();

        $this->newLine();

        if (file_exists(config_path('shopper/admin.php'))) {
            $this->line('  <fg=#EAB308>⚠</> Shopper is already installed in this project.');
            $this->newLine();
            $this->line('  <fg=#475569>To publish assets or run migrations manually, use:</>');
            $this->line('  <fg=#60A5FA>php artisan vendor:publish --tag=shopper-config</>');
            $this->line('  <fg=#60A5FA>php artisan migrate</>');
            $this->newLine();

            return;
        }

        $this->line('  <fg=#3B82F6>◆</> 🛍  <fg=#94A3B8>The headless e-commerce admin panel for Laravel</> <fg=#3B82F6>◆</>');
        $this->newLine();

        $this->task('Publishing configuration files', function (): void {
            $this->callSilently('vendor:publish', ['--tag' => 'shopper-config']);
        });

        $this->task('Publishing media library migrations', function (): void {
            $this->callSilently('vendor:publish', [
                '--provider' => MediaLibraryServiceProvider::class,
                '--tag' => 'medialibrary-migrations',
            ]);
        });

        $this->task('Publishing Filament assets', function (): void {
            $this->callSilently('filament:assets');
        });

        $this->task('Creating storage symlink', function (): void {
            $this->callSilently('shopper:link');
        });

        if (confirm('Run database migrations and seeders?')) {
            $this->newLine();

            $this->task('Running database migrations', function (): void {
                $this->callSilently('migrate');
            });

            $this->task('Seeding domain data', function (): void {
                $this->callSilently('db:seed', ['--class' => ShopperSeeder::class]);
            });

            $this->task('Seeding roles and permissions', function (): void {
                $this->callSilently('db:seed', ['--class' => AuthTableSeeder::class]);
            });
        }

        $this->renderSuccess();
    }

    private function task(string $title, Closure $callback): void
    {
        spin(callback: $callback, message: $title);

        $width = 52;
        $dots = str_repeat('.', max(1, $width - mb_strlen($title)));

        $this->output->writeln("  <fg=#94A3B8>{$title}</> <fg=#334155>{$dots}</> <fg=#22C55E>✓</>");
    }

    private function renderLogo(): void
    {
        $lines = [
            '  ███████╗ ██╗  ██╗  ██████╗  ██████╗  ██████╗  ███████╗ ██████╗',
            '  ██╔════╝ ██║  ██║ ██╔═══██╗ ██╔══██╗ ██╔══██╗ ██╔════╝ ██╔══██╗',
            '  ███████╗ ███████║ ██║   ██║ ██████╔╝ ██████╔╝ █████╗   ██████╔╝',
            '  ╚════██║ ██╔══██║ ██║   ██║ ██╔═══╝  ██╔═══╝  ██╔══╝   ██╔══██╗',
            '  ███████║ ██║  ██║ ╚██████╔╝ ██║      ██║      ███████╗ ██║  ██║',
            '  ╚══════╝ ╚═╝  ╚═╝  ╚═════╝  ╚═╝      ╚═╝      ╚══════╝ ╚═╝  ╚═╝',
        ];

        $colors = ['#1E40AF', '#2563EB', '#3B82F6', '#60A5FA', '#93C5FD', '#BFDBFE'];

        $this->newLine();

        foreach ($lines as $index => $line) {
            $color = $colors[$index];
            $this->line("<fg={$color}>{$line}</>");
        }
    }

    private function renderSuccess(): void
    {
        $this->newLine();
        $this->line('  <fg=#22C55E;options=bold>✓ Shopper has been installed successfully!</>');
        $this->newLine();
        $this->line('  <fg=#475569>Next steps:</>');
        $this->newLine();
        $this->line('  <fg=#3B82F6>→</> Add <options=bold>InteractsWithShopper</> to your <options=bold>User</> model');
        $this->line('  <fg=#3B82F6>→</> Run <fg=#60A5FA>php artisan shopper:user</> to create your first admin');
        $prefix = config('shopper.admin.prefix', 'cpanel');
        $adminUrl = mb_rtrim(config('app.url'), '/').'/'.$prefix;
        $this->line("  <fg=#3B82F6>→</> Visit <fg=#60A5FA>{$adminUrl}</> to access the panel");
        $this->newLine();
        $this->line('  ⭐ Star us on GitHub: <fg=#3B82F6>https://github.com/shopperlabs/shopper</>');
        $this->newLine();
    }
}
