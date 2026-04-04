<?php

declare(strict_types=1);

namespace Shopper\StarterKit;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

final class KitState
{
    private const string FILENAME = '.shopper-kit';

    public function __construct(
        private readonly Filesystem $files,
        private readonly string $basePath,
    ) {}

    public static function hashFile(string $filePath): string
    {
        return hash_file('sha256', $filePath) ?: '';
    }

    public function exists(): bool
    {
        return $this->files->exists($this->path());
    }

    /**
     * @return array{kit: string, version: string, installed_at: string, files: array<string, string>}
     *
     * @throws FileNotFoundException
     */
    public function read(): array
    {
        if (! $this->exists()) {
            return [
                'kit' => '',
                'version' => '',
                'installed_at' => '',
                'files' => [],
            ];
        }

        $content = json_decode($this->files->get($this->path()), true);

        return is_array($content)
            ? $content
            : ['kit' => '', 'version' => '', 'installed_at' => '', 'files' => []];
    }

    /**
     * @param  array<string, string>  $files  path => sha256 hash
     */
    public function write(string $kitId, string $version, array $files): void
    {
        $this->files->put($this->path(), json_encode([
            'kit' => $kitId,
            'version' => $version,
            'installed_at' => now()->toIso8601String(),
            'files' => $files,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function installedKit(): ?string
    {
        $data = $this->read();

        return $data['kit'] !== '' ? $data['kit'] : null;
    }

    public function installedVersion(): ?string
    {
        $data = $this->read();

        return $data['version'] !== '' ? $data['version'] : null;
    }

    private function path(): string
    {
        return mb_rtrim($this->basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.self::FILENAME;
    }
}
