<?php

declare(strict_types=1);

namespace Shopper\StarterKit;

use Closure;
use Composer\InstalledVersions;
use Composer\Semver\Semver;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Shopper\StarterKit\Exceptions\InvalidManifestException;
use Shopper\StarterKit\Exceptions\ManifestNotFoundException;
use Symfony\Component\Process\Process;
use Throwable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;

final class Installer
{
    private const string MANIFEST_FILE = 'shopper-kit.yaml';

    /** @var list<string> */
    private const array BLACKLISTED_PATHS = [
        '.env',
        '.env.*',
        'composer.json',
        'composer.lock',
        'vendor',
        '.git',
        'node_modules',
    ];

    private ?string $repositoryUrl = null;

    private ?Manifest $manifest = null;

    /** @var array<string, string> */
    private array $installedFiles = [];

    public function __construct(
        private readonly Command $command,
        private readonly Filesystem $files,
        private readonly string $basePath,
        private readonly string $kitId,
        private readonly bool $force = false,
        private readonly bool $withDependencies = true,
        private readonly bool $withPostInstall = true,
    ) {}

    public function install(): void
    {
        $this
            ->backupComposerJson()
            ->detectRepositoryUrl()
            ->prepareRepository()
            ->requireKit()
            ->loadManifest()
            ->validateRequirements()
            ->copyFiles()
            ->installDependencies()
            ->runPostInstall()
            ->writeState()
            ->removeKit()
            ->removeRepository()
            ->removeComposerJsonBackup();
    }

    private function backupComposerJson(): self
    {
        $source = base_path('composer.json');
        $backup = base_path('composer.json.bak');

        if ($this->files->exists($source)) {
            $this->files->copy($source, $backup);
        }

        return $this;
    }

    private function detectRepositoryUrl(): self
    {
        $this->task('Checking package availability', function (): void {
            if ($this->isOnPackagist()) {
                return;
            }

            $providers = [
                "https://github.com/{$this->kitId}",
                "https://bitbucket.org/{$this->kitId}.git",
                "https://gitlab.com/{$this->kitId}",
            ];

            foreach ($providers as $url) {
                try {
                    if (Http::timeout(10)->get($url)->successful()) {
                        $this->repositoryUrl = $url;

                        return;
                    }
                } catch (Throwable) {
                    continue;
                }
            }

            $this->abort("Starter kit [{$this->kitId}] was not found on Packagist, GitHub, Bitbucket, or GitLab.");
        });

        return $this;
    }

    private function prepareRepository(): self
    {
        if ($this->repositoryUrl === null) {
            return $this;
        }

        $composerJsonPath = base_path('composer.json');
        $composerJson = json_decode($this->files->get($composerJsonPath), true);

        if (! is_array($composerJson)) {
            $this->abort('Could not parse composer.json. Please ensure it contains valid JSON.');
        }

        $composerJson['repositories'][] = [
            'type' => 'vcs',
            'url' => $this->repositoryUrl,
        ];

        $this->files->put(
            $composerJsonPath,
            json_encode($composerJson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
        );

        return $this;
    }

    private function requireKit(): self
    {
        spin(
            callback: function (): void {
                $process = $this->runProcess(['composer', 'require', $this->kitId, '--no-interaction', '--no-scripts']);

                if (! $process->isSuccessful()) {
                    $this->abort("Failed to download starter kit [{$this->kitId}].\n\n".$process->getErrorOutput());
                }
            },
            message: "Downloading starter kit [{$this->kitId}]...",
        );

        return $this;
    }

    private function loadManifest(): self
    {
        $manifestPath = base_path("vendor/{$this->kitId}/".self::MANIFEST_FILE);

        try {
            $this->manifest = Manifest::fromPath($manifestPath);
        } catch (ManifestNotFoundException|InvalidManifestException $exception) {
            $this->abort($exception->getMessage());
        }

        return $this;
    }

    private function validateRequirements(): self
    {
        if ($this->manifest === null) {
            return $this;
        }

        $this->checkConstraint('PHP', PHP_VERSION, $this->manifest->phpConstraint);

        $laravelVersion = app()->version();
        $this->checkConstraint('Laravel', $laravelVersion, $this->manifest->laravelConstraint);

        $shopperVersion = $this->getShopperVersion();

        if ($shopperVersion !== null) {
            $this->checkConstraint('Shopper', $shopperVersion, $this->manifest->shopperConstraint);
        }

        return $this;
    }

    private function copyFiles(): self
    {
        if ($this->manifest === null) {
            return $this;
        }

        $kitBasePath = base_path("vendor/{$this->kitId}");

        foreach ($this->manifest->exportPaths as $exportPath) {
            $sourcePath = $kitBasePath.'/'.mb_ltrim($exportPath, '/');
            $targetPath = mb_rtrim($this->basePath, '/').'/'.mb_ltrim($exportPath, '/');

            if (! $this->files->exists($sourcePath)) {
                warning("  Export path [{$exportPath}] not found in the starter kit, skipping.");

                continue;
            }

            if ($this->files->isDirectory($sourcePath)) {
                $this->copyDirectory($sourcePath, $targetPath, $exportPath);
            } else {
                $this->copySingleFile($sourcePath, $targetPath, $exportPath);
            }
        }

        return $this;
    }

    private function installDependencies(): self
    {
        if (! $this->withDependencies || $this->manifest === null) {
            return $this;
        }

        if ($this->manifest->dependencies !== []) {
            $packages = collect($this->manifest->dependencies)
                ->map(fn (string $version, string $package): string => "{$package}:{$version}")
                ->values()
                ->all();

            $this->task('Installing dependencies', function () use ($packages): void {
                $process = $this->runProcess(array_merge(
                    ['composer', 'require', '--no-interaction'],
                    $packages,
                ));

                if (! $process->isSuccessful()) {
                    warning('  Some dependencies could not be installed: '.$process->getErrorOutput());
                }
            });
        }

        if ($this->manifest->devDependencies !== []) {
            $packages = collect($this->manifest->devDependencies)
                ->map(fn (string $version, string $package): string => "{$package}:{$version}")
                ->values()
                ->all();

            $this->task('Installing dev dependencies', function () use ($packages): void {
                $process = $this->runProcess(array_merge(
                    ['composer', 'require', '--dev', '--no-interaction'],
                    $packages,
                ));

                if (! $process->isSuccessful()) {
                    warning('  Some dev dependencies could not be installed: '.$process->getErrorOutput());
                }
            });
        }

        return $this;
    }

    private function runPostInstall(): self
    {
        if (! $this->withPostInstall || $this->manifest === null || $this->manifest->postInstall === []) {
            return $this;
        }

        if (! $this->force) {
            $this->command->newLine();
            info('The starter kit wants to run the following post-install commands:');

            foreach ($this->manifest->postInstall as $postInstallCommand) {
                $this->command->line("  <fg=#60A5FA>→</> {$postInstallCommand}");
            }

            $this->command->newLine();

            if (! confirm('Run these commands?')) {
                warning('  Post-install commands skipped.');

                return $this;
            }
        }

        foreach ($this->manifest->postInstall as $postInstallCommand) {
            $this->task("Running: {$postInstallCommand}", function () use ($postInstallCommand): void {
                $process = preg_match('/[|&;><]/', $postInstallCommand)
                    ? new Process(['sh', '-c', $postInstallCommand], $this->basePath)
                    : new Process(array_values(array_filter(str_getcsv($postInstallCommand, ' '))), $this->basePath);
                $process->setTimeout(300);
                $process->run();

                if (! $process->isSuccessful()) {
                    warning("  Command failed: {$postInstallCommand}");
                    warning('  '.$process->getErrorOutput());
                }
            });
        }

        return $this;
    }

    private function writeState(): self
    {
        if ($this->manifest === null) {
            return $this;
        }

        try {
            $state = new KitState($this->files, $this->basePath);
            $state->write($this->kitId, $this->manifest->version, $this->installedFiles);
        } catch (Throwable) {
            warning('  Could not write .shopper-kit state file.');
        }

        return $this;
    }

    private function removeKit(): self
    {
        spin(
            callback: function (): void {
                $process = $this->runProcess(['composer', 'remove', $this->kitId, '--no-interaction']);

                if (! $process->isSuccessful()) {
                    warning("  Could not remove starter kit package: {$process->getErrorOutput()}");
                }
            },
            message: 'Cleaning up...',
        );

        return $this;
    }

    private function removeRepository(): self
    {
        if ($this->repositoryUrl === null) {
            return $this;
        }

        $composerJsonPath = base_path('composer.json');

        if (! $this->files->exists($composerJsonPath)) {
            return $this;
        }

        $composerJson = json_decode($this->files->get($composerJsonPath), true);

        if (! is_array($composerJson)) {
            return $this;
        }

        if (! isset($composerJson['repositories'])) {
            return $this;
        }

        $composerJson['repositories'] = array_values(
            array_filter(
                $composerJson['repositories'],
                fn (array $repository): bool => ($repository['url'] ?? '') !== $this->repositoryUrl,
            ),
        );

        if ($composerJson['repositories'] === []) {
            unset($composerJson['repositories']);
        }

        $this->files->put(
            $composerJsonPath,
            json_encode($composerJson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
        );

        return $this;
    }

    private function removeComposerJsonBackup(): self
    {
        $backup = base_path('composer.json.bak');

        if ($this->files->exists($backup)) {
            $this->files->delete($backup);
        }

        return $this;
    }

    private function copyDirectory(string $sourcePath, string $targetPath, string $exportPath): void
    {
        $sourceFiles = $this->files->allFiles($sourcePath);

        foreach ($sourceFiles as $file) {
            $relativePath = $exportPath.'/'.mb_ltrim($file->getRelativePathname(), '/');
            $fileTargetPath = mb_rtrim($this->basePath, '/').'/'.$relativePath;

            $this->copySingleFile($file->getPathname(), $fileTargetPath, $relativePath);
        }
    }

    private function copySingleFile(string $sourcePath, string $targetPath, string $relativePath): void
    {
        if ($this->isBlacklisted($relativePath)) {
            warning("  Skipping blacklisted path: [{$relativePath}]");

            return;
        }

        if ($this->hasPathTraversal($relativePath)) {
            warning("  Skipping unsafe path: [{$relativePath}]");

            return;
        }

        if ($this->files->exists($targetPath) && ! $this->force) {
            if (! confirm("  File [{$relativePath}] already exists. Overwrite?", default: false)) {
                return;
            }
        }

        $directory = dirname($targetPath);

        if (! $this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $this->files->copy($sourcePath, $targetPath);

        $this->installedFiles[$relativePath] = KitState::hashFile($targetPath);
    }

    private function isBlacklisted(string $path): bool
    {
        $normalized = mb_ltrim($path, '/');

        foreach (self::BLACKLISTED_PATHS as $blacklisted) {
            if ($normalized === $blacklisted || str_starts_with($normalized, $blacklisted.'/')) {
                return true;
            }

            if (str_contains($blacklisted, '.') && fnmatch($blacklisted, $normalized)) {
                return true;
            }
        }

        return false;
    }

    private function hasPathTraversal(string $path): bool
    {
        return str_contains($path, '..') || str_starts_with($path, '/');
    }

    private function isOnPackagist(): bool
    {
        try {
            return Http::timeout(10)
                ->get("https://repo.packagist.org/p2/{$this->kitId}.json")
                ->successful();
        } catch (Throwable) {
            return false;
        }
    }

    private function checkConstraint(string $name, string $currentVersion, string $constraint): void
    {
        if ($constraint === '*') {
            return;
        }

        try {
            if (! Semver::satisfies($currentVersion, $constraint)) {
                warning("  {$name} version [{$currentVersion}] does not satisfy the kit requirement [{$constraint}].");

                if (! $this->force && ! confirm('  Continue anyway?', default: false)) {
                    $this->abort("{$name} version mismatch.");
                }
            }
        } catch (Throwable) {
            // If semver parsing fails, skip the check
        }
    }

    private function getShopperVersion(): ?string
    {
        try {
            return InstalledVersions::getPrettyVersion('shopper/framework');
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @param  list<string>  $command
     */
    private function runProcess(array $command): Process
    {
        $process = new Process($command, base_path());
        $process->setTimeout(300);
        $process->run();

        return $process;
    }

    private function task(string $title, Closure $callback): void
    {
        spin(callback: $callback, message: $title);

        $width = 52;
        $dots = str_repeat('.', max(1, $width - mb_strlen($title)));

        $this->command->getOutput()->writeln("  <fg=#94A3B8>{$title}</> <fg=#334155>{$dots}</> <fg=#22C55E>✓</>");
    }

    private function abort(string $message): never
    {
        $this->restoreComposerJson();

        throw new RuntimeException($message);
    }

    private function restoreComposerJson(): void
    {
        $backup = base_path('composer.json.bak');

        if ($this->files->exists($backup)) {
            $this->files->copy($backup, base_path('composer.json'));
            $this->files->delete($backup);
        }
    }
}
