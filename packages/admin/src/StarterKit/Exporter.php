<?php

declare(strict_types=1);

namespace Shopper\StarterKit;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Shopper\StarterKit\Concerns\HasConsoleTask;
use Shopper\StarterKit\Exceptions\InvalidManifestException;
use Shopper\StarterKit\Exceptions\ManifestNotFoundException;
use Symfony\Component\Yaml\Yaml;

use function Laravel\Prompts\warning;

final class Exporter
{
    use HasConsoleTask;

    private const string MANIFEST_FILE = 'shopper-kit.yaml';

    /** @var list<string> */
    private const array FORBIDDEN_EXPORTS = [
        'composer.json',
        'composer.lock',
        'shopper-kit.yaml',
        '.env',
        'vendor',
        'node_modules',
        '.git',
    ];

    private Manifest $manifest;

    public function __construct(
        private readonly Command $command,
        private readonly Filesystem $files,
        private readonly string $exportPath,
        private readonly bool $clear = false,
    ) {}

    public function export(): void
    {
        $this
            ->loadManifest()
            ->validateExportPaths()
            ->validateDependencies()
            ->clearExportPath()
            ->copyFiles()
            ->versionDependencies()
            ->exportManifest()
            ->exportComposerJson();
    }

    private function loadManifest(): self
    {
        $manifestPath = mb_rtrim($this->exportPath, '/').'/'.self::MANIFEST_FILE;

        try {
            $this->manifest = Manifest::fromPath($manifestPath);
        } catch (ManifestNotFoundException) {
            $this->abort("No shopper-kit.yaml found at [{$this->exportPath}]. Run shopper:kit:init first.");
        } catch (InvalidManifestException $exception) {
            $this->abort($exception->getMessage());
        }

        return $this;
    }

    private function validateExportPaths(): self
    {
        foreach ($this->manifest->exportPaths as $path) {
            if ($this->isForbidden($path)) {
                $this->abort("Export path [{$path}] is forbidden and cannot be exported.");
            }

            $sourcePath = base_path($path);

            if (! $this->files->exists($sourcePath)) {
                $this->abort("Export path [{$path}] does not exist in your project.");
            }
        }

        return $this;
    }

    private function validateDependencies(): self
    {
        $composerJson = $this->readProjectComposerJson();

        if ($composerJson === null) {
            return $this;
        }

        $installed = array_merge(
            $composerJson['require'] ?? [],
            $composerJson['require-dev'] ?? [],
        );

        foreach (array_keys($this->manifest->dependencies) as $package) {
            if (! isset($installed[$package])) {
                warning("  Dependency [{$package}] is listed in shopper-kit.yaml but not installed in your project.");
            }
        }

        foreach (array_keys($this->manifest->devDependencies) as $package) {
            if (! isset($installed[$package])) {
                warning("  Dev dependency [{$package}] is listed in shopper-kit.yaml but not installed in your project.");
            }
        }

        return $this;
    }

    private function clearExportPath(): self
    {
        if (! $this->clear) {
            return $this;
        }

        $this->task('Clearing export path', function (): void {
            $gitPath = mb_rtrim($this->exportPath, '/').'/.git';
            $hasGit = $this->files->isDirectory($gitPath);
            $tempGitPath = sys_get_temp_dir().'/shopper-kit-git-'.bin2hex(random_bytes(8));

            if ($hasGit) {
                $this->files->moveDirectory($gitPath, $tempGitPath);
            }

            foreach ($this->files->glob(mb_rtrim($this->exportPath, '/').'/*') as $item) {
                if ($this->files->isDirectory($item)) {
                    $this->files->deleteDirectory($item);
                } else {
                    $this->files->delete($item);
                }
            }

            // Also remove hidden files (except .git which was moved)
            foreach ($this->files->glob(mb_rtrim($this->exportPath, '/').'/.[!.]*') as $item) {
                if ($this->files->isDirectory($item)) {
                    $this->files->deleteDirectory($item);
                } else {
                    $this->files->delete($item);
                }
            }

            if ($hasGit) {
                $this->files->moveDirectory($tempGitPath, $gitPath);
            }
        });

        return $this;
    }

    private function copyFiles(): self
    {
        $this->task('Exporting files', function (): void {
            foreach ($this->manifest->exportPaths as $exportPath) {
                $sourcePath = base_path($exportPath);
                $targetPath = mb_rtrim($this->exportPath, '/').'/'.$exportPath;

                if ($this->files->isDirectory($sourcePath)) {
                    $this->copyDirectory($sourcePath, $targetPath);
                } else {
                    $this->copySingleFile($sourcePath, $targetPath);
                }
            }
        });

        return $this;
    }

    private function versionDependencies(): self
    {
        $composerJson = $this->readProjectComposerJson();

        if ($composerJson === null) {
            return $this;
        }

        $require = $composerJson['require'] ?? [];
        $requireDev = $composerJson['require-dev'] ?? [];

        $this->manifest = new Manifest(
            name: $this->manifest->name,
            description: $this->manifest->description,
            version: $this->manifest->version,
            author: $this->manifest->author,
            url: $this->manifest->url,
            shopperConstraint: $this->manifest->shopperConstraint,
            phpConstraint: $this->manifest->phpConstraint,
            laravelConstraint: $this->manifest->laravelConstraint,
            exportPaths: $this->manifest->exportPaths,
            dependencies: $this->resolveVersions($this->manifest->dependencies, $require),
            devDependencies: $this->resolveVersions($this->manifest->devDependencies, $requireDev),
            postInstall: $this->manifest->postInstall,
        );

        return $this;
    }

    private function exportManifest(): self
    {
        $this->task('Writing shopper-kit.yaml', function (): void {
            $yaml = $this->buildYaml();

            $this->files->put(
                mb_rtrim($this->exportPath, '/').'/'.self::MANIFEST_FILE,
                $yaml,
            );
        });

        return $this;
    }

    private function buildYaml(): string
    {
        $sections = [];

        $sections[] = Yaml::dump([
            'name' => $this->manifest->name,
            'description' => $this->manifest->description,
            'version' => $this->manifest->version,
            'author' => $this->manifest->author,
            'url' => $this->manifest->url,
        ]);

        $sections[] = Yaml::dump([
            'shopper' => $this->manifest->shopperConstraint,
            'php' => $this->manifest->phpConstraint,
            'laravel' => $this->manifest->laravelConstraint,
        ]);

        $sections[] = Yaml::dump(['export_paths' => $this->manifest->exportPaths], 4, 2);

        if ($this->manifest->dependencies !== []) {
            $sections[] = Yaml::dump(['dependencies' => $this->manifest->dependencies], 4, 2);
        }

        if ($this->manifest->devDependencies !== []) {
            $sections[] = Yaml::dump(['dev_dependencies' => $this->manifest->devDependencies], 4, 2);
        }

        if ($this->manifest->postInstall !== []) {
            $sections[] = Yaml::dump(['post_install' => $this->manifest->postInstall], 4, 2);
        }

        return implode("\n", $sections);
    }

    private function exportComposerJson(): self
    {
        $composerJsonPath = mb_rtrim($this->exportPath, '/').'/composer.json';

        if (! $this->files->exists($composerJsonPath)) {
            return $this;
        }

        // Touch the file to update its modification time
        touch($composerJsonPath);

        return $this;
    }

    private function copyDirectory(string $sourcePath, string $targetPath): void
    {
        foreach ($this->files->allFiles($sourcePath) as $file) {
            $relativePath = $file->getRelativePathname();
            $fileTargetPath = mb_rtrim($targetPath, '/').'/'.$relativePath;

            $this->copySingleFile($file->getPathname(), $fileTargetPath);
        }
    }

    private function copySingleFile(string $sourcePath, string $targetPath): void
    {
        $directory = dirname($targetPath);

        if (! $this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $this->files->copy($sourcePath, $targetPath);
    }

    /**
     * @param  array<string, string>  $manifestDeps
     * @param  array<string, string>  $composerDeps
     * @return array<string, string>
     */
    private function resolveVersions(array $manifestDeps, array $composerDeps): array
    {
        $resolved = [];

        foreach ($manifestDeps as $package => $version) {
            $resolved[$package] = $composerDeps[$package] ?? $version;
        }

        return $resolved;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function readProjectComposerJson(): ?array
    {
        $composerJsonPath = base_path('composer.json');

        if (! $this->files->exists($composerJsonPath)) {
            return null;
        }

        $composerJson = json_decode($this->files->get($composerJsonPath), true);

        return is_array($composerJson) ? $composerJson : null;
    }

    private function taskOutput(): \Symfony\Component\Console\Output\OutputInterface
    {
        return $this->command->getOutput();
    }

    private function isForbidden(string $path): bool
    {
        $normalized = mb_ltrim($path, '/');

        foreach (self::FORBIDDEN_EXPORTS as $forbidden) {
            if ($normalized === $forbidden || str_starts_with($normalized, $forbidden.'/')) {
                return true;
            }
        }

        return false;
    }

    private function abort(string $message): never
    {
        throw new RuntimeException($message);
    }
}
