<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Shopper\StarterKit\Concerns\HasConsoleTask;
use Shopper\StarterKit\Manifest;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

#[AsCommand(name: 'shopper:kit:init')]
final class KitInitCommand extends Command
{
    use HasConsoleTask;

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

        /** @var list<string> $stack */
        $stack = multisearch(
            label: 'Stack',
            options: fn (string $value): array => array_filter(
                Manifest::STACKS,
                fn (string $label): bool => $value === '' || mb_stripos($label, $value) !== false,
            ),
            required: true,
            hint: 'Use the space bar to select the technologies shown on the listing.',
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

        $this->task('Creating directory', function () use ($files, $directory): void {
            $files->makeDirectory($directory, 0755, true);
        });

        $this->task('Generating composer.json', function () use ($files, $directory, $package, $description, $author): void {
            $files->put($directory.'/composer.json', json_encode([
                'name' => $package,
                'description' => $description,
                'license' => 'MIT',
                'authors' => [
                    [
                        'name' => $author,
                    ],
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        });

        $this->task('Generating shopper-kit.yaml', function () use ($files, $directory, $name, $description, $author, $stack): void {
            $manifest = new Manifest(
                name: $name,
                description: $description,
                version: '1.0.0',
                author: $author,
                url: '',
                shopperConstraint: '^3.0',
                phpConstraint: '^8.3',
                laravelConstraint: '^12.68|^13.27',
                exportPaths: ['resources/views', 'resources/css', 'resources/js', 'routes'],
                stack: $stack,
            );

            $files->put($directory.'/shopper-kit.yaml', $manifest->toYaml());
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

            See the [Shopper documentation](https://docs.shopperphp.com) for more details on building starter kits.
            MD;

            $files->put($directory.'/README.md', $readme);
        });

        $this->newLine();
        $this->line('  <fg=#22C55E;options=bold>✓ Starter kit scaffolded successfully!</>');
        $this->newLine();
        $this->line("  <fg=#475569>Created at:</> <options=bold>{$directory}</>");
        $this->newLine();
        $this->line('  <fg=#475569>Next steps:</>');
        $relativePath = $this->relativePath($directory);

        $this->line('  <fg=#3B82F6>→</> Edit <options=bold>shopper-kit.yaml</> to configure export_paths');
        $this->line('  <fg=#3B82F6>→</> Run <options=bold>php artisan shopper:kit:export '.$relativePath.'</> to export files from your project');
        $this->line('  <fg=#3B82F6>→</> Fill <options=bold>preview</>, <options=bold>docs</> and <options=bold>screenshots</> so the kit has a listing');
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

        return base_path($name);
    }

    private function relativePath(string $absolutePath): string
    {
        $basePath = base_path().'/';

        if (str_starts_with($absolutePath, $basePath)) {
            return mb_substr($absolutePath, mb_strlen($basePath));
        }

        return $absolutePath;
    }

    private function taskOutput(): \Symfony\Component\Console\Output\OutputInterface
    {
        return $this->output;
    }
}
