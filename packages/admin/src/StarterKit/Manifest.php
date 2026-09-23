<?php

declare(strict_types=1);

namespace Shopper\StarterKit;

use Shopper\StarterKit\Exceptions\InvalidManifestException;
use Shopper\StarterKit\Exceptions\ManifestNotFoundException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final readonly class Manifest
{
    /** The technologies the starter kits listing knows. A kit may declare others: kept, not shown. */
    public const array STACKS = [
        'livewire' => 'Livewire',
        'blade' => 'Blade',
        'inertia' => 'Inertia',
        'alpinejs' => 'Alpine.js',
        'react' => 'React',
        'vue' => 'Vue',
        'svelte' => 'Svelte',
        'angular' => 'Angular',
        'nextjs' => 'Next.js',
        'nuxt' => 'Nuxt',
        'remix' => 'Remix',
        'tanstack' => 'TanStack',
        'astro' => 'Astro',
        'expo' => 'Expo',
        'flutter' => 'Flutter',
    ];

    /**
     * @param  list<string>  $exportPaths
     * @param  array<string, string>  $dependencies
     * @param  array<string, string>  $devDependencies
     * @param  list<string>  $postInstall
     * @param  list<string>  $screenshots
     * @param  list<string>  $stack
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
        public string $preview = '',
        public string $docs = '',
        public array $screenshots = [],
        public array $stack = [],
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
            preview: (string) ($data['preview'] ?? ''),
            docs: (string) ($data['docs'] ?? ''),
            screenshots: array_values(array_filter((array) ($data['screenshots'] ?? []), is_string(...))),
            stack: array_values(array_filter((array) ($data['stack'] ?? []), is_string(...))),
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
     * @param  array<string, string>  $dependencies
     * @param  array<string, string>  $devDependencies
     */
    public function withDependencies(array $dependencies, array $devDependencies): self
    {
        return new self(...[...get_object_vars($this), 'dependencies' => $dependencies, 'devDependencies' => $devDependencies]);
    }

    /** @return list<string> */
    public function unknownStack(): array
    {
        return array_values(array_diff($this->stack, array_keys(self::STACKS)));
    }

    public function toYaml(): string
    {
        $header = [
            'name' => $this->name,
            'description' => $this->description,
            'version' => $this->version,
            'author' => $this->author,
            'url' => $this->url,
            'preview' => $this->preview,
            'docs' => $this->docs,
        ];

        if ($this->stack !== []) {
            $header['stack'] = $this->stack;
        }

        $sections = [
            Yaml::dump($header, 1, 2),
            Yaml::dump(['shopper' => $this->shopperConstraint, 'php' => $this->phpConstraint, 'laravel' => $this->laravelConstraint], 1, 2),
        ];

        $lists = [
            'screenshots' => $this->screenshots,
            'export_paths' => $this->exportPaths,
            'dependencies' => $this->dependencies,
            'dev_dependencies' => $this->devDependencies,
            'post_install' => $this->postInstall,
        ];

        foreach ($lists as $key => $values) {
            if ($values !== []) {
                $sections[] = Yaml::dump([$key => $values], 4, 2);
            }
        }

        return implode("\n", $sections);
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
