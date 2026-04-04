<?php

declare(strict_types=1);

namespace Shopper\Console;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use stdClass;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

#[AsCommand(name: 'shopper:kit:init')]
final class KitInitCommand extends Command
{
    protected $signature = 'shopper:kit:init
        {--path= : Directory where the kit will be created}';

    protected $description = 'Scaffold a new Shopper starter kit for development';

    public function handle(Filesystem $files): int
    {
        $this->newLine();
        $this->line('  <fg=#3B82F6>◆</> Create a new Shopper starter kit');
        $this->newLine();

        $name = text(
            label: 'Kit name',
            placeholder: 'My Awesome Storefront',
            required: true,
        );

        $package = text(
            label: 'Package name (vendor/name)',
            placeholder: 'acme/starter-awesome',
            required: true,
            validate: fn (string $value): ?string => preg_match('/^[a-z0-9-]+\/[a-z0-9-]+$/', $value)
                ? null
                : 'Package name must be in vendor/name format (lowercase, hyphens allowed).',
        );

        $description = text(
            label: 'Description',
            placeholder: 'A modern storefront with Livewire components',
        );

        $author = text(
            label: 'Author',
            placeholder: 'acme',
            default: explode('/', $package)[0],
        );

        $directory = $this->resolveDirectory($package);

        if ($files->isDirectory($directory)) {
            warning("  Directory [{$directory}] already exists.");

            if (! confirm('Overwrite?', default: false)) {
                return self::SUCCESS;
            }

            $files->deleteDirectory($directory);
        }

        $this->newLine();

        $this->task('Creating directory structure', function () use ($files, $directory): void {
            $files->makeDirectory($directory.'/resources/views', 0755, true);
            $files->makeDirectory($directory.'/resources/css', 0755, true);
            $files->makeDirectory($directory.'/resources/js', 0755, true);
            $files->makeDirectory($directory.'/routes', 0755, true);

            $files->put($directory.'/resources/views/.gitkeep', '');
            $files->put($directory.'/resources/css/.gitkeep', '');
            $files->put($directory.'/resources/js/.gitkeep', '');
            $files->put($directory.'/routes/.gitkeep', '');
        });

        $this->task('Generating composer.json', function () use ($files, $directory, $package, $description): void {
            $files->put($directory.'/composer.json', json_encode([
                'name' => $package,
                'description' => $description,
                'type' => 'shopper-starter-kit',
                'license' => 'MIT',
                'require' => new stdClass,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        });

        $this->task('Generating shopper-kit.yaml', function () use ($files, $directory, $name, $description, $author): void {
            $yaml = <<<YAML
            name: "{$name}"
            description: "{$description}"
            version: "1.0.0"
            author: "{$author}"
            url: ""
            shopper: "^2.7"
            php: "^8.3"
            laravel: "^11.48|^12.0"

            export_paths:
              - resources/views
              - resources/css
              - resources/js
              - routes

            dependencies: []

            dev_dependencies: []

            post_install: []
            YAML;

            $files->put($directory.'/shopper-kit.yaml', $yaml);
        });

        $this->task('Generating README.md', function () use ($files, $directory, $name, $package): void {
            $readme = <<<MD
            # {$name}

            A Shopper starter kit.

            ## Installation

            ```bash
            php artisan shopper:kit:install {$package}
            ```

            ## Development

            Add your storefront files and configure `shopper-kit.yaml` to declare which paths should be exported.

            See the [Shopper documentation](https://docs.laravelshopper.dev) for more details on building starter kits.
            MD;

            $files->put($directory.'/README.md', $readme);
        });

        $this->newLine();
        $this->line('  <fg=#22C55E;options=bold>✓ Starter kit scaffolded successfully!</>');
        $this->newLine();
        $this->line("  <fg=#475569>Created at:</> <options=bold>{$directory}</>");
        $this->newLine();
        $this->line('  <fg=#475569>Next steps:</>');
        $this->line('  <fg=#3B82F6>→</> Add your storefront files (<options=bold>resources/</>, <options=bold>routes/</>)');
        $this->line('  <fg=#3B82F6>→</> Edit <options=bold>shopper-kit.yaml</> to configure export_paths');
        $this->line('  <fg=#3B82F6>→</> Publish on GitHub or Packagist');
        $this->newLine();

        return self::SUCCESS;
    }

    private function resolveDirectory(string $package): string
    {
        if ($this->option('path')) {
            return mb_rtrim($this->option('path'), '/');
        }

        $name = explode('/', $package)[1];

        return getcwd().'/'.$name;
    }

    private function task(string $title, Closure $callback): void
    {
        spin(callback: $callback, message: $title);

        $width = 52;
        $dots = str_repeat('.', max(1, $width - mb_strlen($title)));

        $this->output->writeln("  <fg=#94A3B8>{$title}</> <fg=#334155>{$dots}</> <fg=#22C55E>✓</>");
    }
}
