<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Shopper\StarterKit\Installer;
use Shopper\StarterKit\KitState;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\warning;

#[AsCommand(name: 'shopper:kit:install')]
final class KitInstallCommand extends Command
{
    protected $signature = 'shopper:kit:install
        {kit : The starter kit package (e.g. shopperlabs/starter-kit-blade)}
        {--path= : Install files into a specific directory}
        {--force : Overwrite existing files without confirmation}
        {--no-deps : Skip installing Composer dependencies from the manifest}
        {--no-post-install : Skip post-install commands}';

    protected $description = 'Install a Shopper storefront starter kit';

    public function handle(Filesystem $files): int
    {
        $kitId = $this->argument('kit');
        $basePath = $this->resolveBasePath();

        $this->newLine();
        $this->line('  <fg=#3B82F6>◆</> Installing starter kit: <options=bold>'.$kitId.'</>');
        $this->newLine();

        $state = new KitState($files, $basePath);

        if ($state->exists() && ! $this->option('force')) {
            $installedKit = $state->installedKit();

            warning("  A starter kit [{$installedKit}] is already installed in this project.");

            if (! confirm('Continue and overwrite?', default: false)) {
                return self::SUCCESS;
            }
        }

        try {
            $installer = new Installer(
                command: $this,
                files: $files,
                basePath: $basePath,
                kitId: $kitId,
                force: (bool) $this->option('force'),
                withDependencies: ! $this->option('no-deps'),
                withPostInstall: ! $this->option('no-post-install'),
            );

            $installer->install();
        } catch (RuntimeException $exception) {
            $this->newLine();
            $this->line('  <fg=#EF4444;options=bold>✗ Installation failed.</>');
            $this->line("  <fg=#94A3B8>{$exception->getMessage()}</>");
            $this->newLine();

            return self::FAILURE;
        }

        $this->newLine();
        $this->line('  <fg=#22C55E;options=bold>✓ Starter kit installed successfully!</>');
        $this->newLine();
        $this->line('  <fg=#475569>The starter kit files are now part of your project.</>');
        $this->line('  <fg=#475569>You can customize everything — the code is yours.</>');
        $this->newLine();

        return self::SUCCESS;
    }

    private function resolveBasePath(): string
    {
        $path = $this->option('path');

        if ($path === null) {
            return base_path();
        }

        $resolved = realpath($path);

        return $resolved !== false ? $resolved : $path;
    }
}
