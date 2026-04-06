<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Shopper\StarterKit\Exporter;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;

#[AsCommand(name: 'shopper:kit:export')]
final class KitExportCommand extends Command
{
    protected $signature = 'shopper:kit:export
        {path : The path to the starter kit directory}
        {--clear : Clear the export path before exporting (preserves .git)}';

    protected $description = 'Export your project files into a starter kit';

    public function handle(Filesystem $files): int
    {
        $path = $this->resolvePath();

        $this->newLine();
        $this->line('  <fg=#3B82F6>◆</> Exporting starter kit');
        $this->newLine();

        if (! $files->isDirectory($path)) {
            if (! confirm("  Path [{$path}] does not exist. Create it?")) {
                return self::SUCCESS;
            }

            $files->makeDirectory($path, 0755, true);
        }

        try {
            $exporter = new Exporter(
                command: $this,
                files: $files,
                exportPath: $path,
                clear: (bool) $this->option('clear'),
            );

            $exporter->export();
        } catch (RuntimeException $exception) {
            $this->newLine();
            $this->line('  <fg=#EF4444;options=bold>✗ Export failed.</>');
            $this->line("  <fg=#94A3B8>{$exception->getMessage()}</>");
            $this->newLine();

            return self::FAILURE;
        }

        $this->newLine();
        $this->line('  <fg=#22C55E;options=bold>✓ Starter kit exported successfully!</>');
        $this->newLine();
        $this->line("  <fg=#475569>Exported to:</> <options=bold>{$path}</>");
        $this->newLine();

        return self::SUCCESS;
    }

    private function resolvePath(): string
    {
        $path = $this->argument('path');

        if (str_starts_with($path, '/')) {
            return $path;
        }

        return base_path($path);
    }
}
