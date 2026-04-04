<?php

declare(strict_types=1);

namespace Shopper\StarterKit;

use Shopper\StarterKit\Exceptions\InvalidManifestException;
use Shopper\StarterKit\Exceptions\ManifestNotFoundException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final readonly class Manifest
{
    /**
     * @param  list<string>  $exportPaths
     * @param  array<string, string>  $dependencies
     * @param  array<string, string>  $devDependencies
     * @param  list<string>  $postInstall
     */
    public function __construct(
        public string $name,
        public string $description,
        public string $version,
        public string $author,
        public string $url,
        public string $shopperConstraint,
        public string $phpConstraint,
        public string $laravelConstraint,
        public array $exportPaths,
        public array $dependencies = [],
        public array $devDependencies = [],
        public array $postInstall = [],
    ) {}

    public static function fromYaml(string $yamlContent): self
    {
        try {
            $data = Yaml::parse($yamlContent);
        } catch (ParseException $exception) {
            throw InvalidManifestException::invalidYaml($exception->getMessage());
        }

        if (! is_array($data)) {
            throw InvalidManifestException::invalidYaml('The manifest content is not a valid YAML mapping.');
        }

        self::validateRequiredFields($data);

        return new self(
            name: (string) $data['name'],
            description: (string) ($data['description'] ?? ''),
            version: (string) ($data['version'] ?? '0.0.0'),
            author: (string) ($data['author'] ?? ''),
            url: (string) ($data['url'] ?? ''),
            shopperConstraint: (string) ($data['shopper'] ?? '*'),
            phpConstraint: (string) ($data['php'] ?? '*'),
            laravelConstraint: (string) ($data['laravel'] ?? '*'),
            exportPaths: array_values(array_map(strval(...), (array) $data['export_paths'])),
            dependencies: self::parseDependencies($data['dependencies'] ?? []),
            devDependencies: self::parseDependencies($data['dev_dependencies'] ?? []),
            postInstall: array_values(array_map(strval(...), (array) ($data['post_install'] ?? []))),
        );
    }

    public static function fromPath(string $path): self
    {
        if (! file_exists($path)) {
            throw ManifestNotFoundException::atPath($path);
        }

        $content = file_get_contents($path);

        if ($content === false) {
            throw ManifestNotFoundException::atPath($path);
        }

        return self::fromYaml($content);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function validateRequiredFields(array $data): void
    {
        if (! isset($data['name']) || mb_trim((string) $data['name']) === '') {
            throw InvalidManifestException::missingField('name');
        }

        if (! isset($data['export_paths']) || ! is_array($data['export_paths']) || $data['export_paths'] === []) {
            throw InvalidManifestException::missingField('export_paths');
        }
    }

    /**
     * @return array<string, string>
     */
    private static function parseDependencies(mixed $dependencies): array
    {
        if (! is_array($dependencies)) {
            return [];
        }

        $parsed = [];

        foreach ($dependencies as $key => $value) {
            if (is_string($key) && is_string($value)) {
                $parsed[$key] = $value;
            } elseif (is_array($value)) {
                foreach ($value as $package => $version) {
                    if (is_string($package) && is_string($version)) {
                        $parsed[$package] = $version;
                    }
                }
            }
        }

        return $parsed;
    }
}
