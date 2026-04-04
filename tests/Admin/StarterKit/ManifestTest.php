<?php

declare(strict_types=1);

use Shopper\StarterKit\Exceptions\InvalidManifestException;
use Shopper\StarterKit\Exceptions\ManifestNotFoundException;
use Shopper\StarterKit\Manifest;

it('parses a valid YAML manifest', function (): void {
    $yaml = <<<'YAML'
    name: "Shopper Blade Starter"
    description: "A minimal Laravel/Blade storefront."
    version: "1.0.0"
    author: "shopperlabs"
    url: "https://github.com/shopperlabs/starter-blade"
    shopper: "^2.0"
    php: "^8.3"
    laravel: "^11.0|^12.0"

    export_paths:
      - resources/views
      - resources/css
      - routes/storefront.php

    dependencies:
      livewire/livewire: "^3.0"

    dev_dependencies:
      pestphp/pest: "^3.0"

    post_install:
      - php artisan migrate
      - php artisan storage:link
    YAML;

    $manifest = Manifest::fromYaml($yaml);

    expect($manifest)
        ->name->toBe('Shopper Blade Starter')
        ->description->toBe('A minimal Laravel/Blade storefront.')
        ->version->toBe('1.0.0')
        ->author->toBe('shopperlabs')
        ->url->toBe('https://github.com/shopperlabs/starter-blade')
        ->shopperConstraint->toBe('^2.0')
        ->phpConstraint->toBe('^8.3')
        ->laravelConstraint->toBe('^11.0|^12.0')
        ->exportPaths->toBe(['resources/views', 'resources/css', 'routes/storefront.php'])
        ->dependencies->toBe(['livewire/livewire' => '^3.0'])
        ->devDependencies->toBe(['pestphp/pest' => '^3.0'])
        ->postInstall->toBe(['php artisan migrate', 'php artisan storage:link']);
});

it('parses a minimal YAML manifest with defaults', function (): void {
    $yaml = <<<'YAML'
    name: "Minimal Kit"
    export_paths:
      - resources/views
    YAML;

    $manifest = Manifest::fromYaml($yaml);

    expect($manifest)
        ->name->toBe('Minimal Kit')
        ->description->toBe('')
        ->version->toBe('0.0.0')
        ->author->toBe('')
        ->url->toBe('')
        ->shopperConstraint->toBe('*')
        ->phpConstraint->toBe('*')
        ->laravelConstraint->toBe('*')
        ->exportPaths->toBe(['resources/views'])
        ->dependencies->toBe([])
        ->devDependencies->toBe([])
        ->postInstall->toBe([]);
});

it('throws when `name` field is missing', function (): void {
    $yaml = <<<'YAML'
    export_paths:
      - resources/views
    YAML;

    Manifest::fromYaml($yaml);
})->throws(InvalidManifestException::class, 'missing the required field: [name]');

it('throws when `name` field is empty', function (): void {
    $yaml = <<<'YAML'
    name: ""
    export_paths:
      - resources/views
    YAML;

    Manifest::fromYaml($yaml);
})->throws(InvalidManifestException::class, 'missing the required field: [name]');

it('throws when `export_paths` field is missing', function (): void {
    $yaml = <<<'YAML'
    name: "Test Kit"
    YAML;

    Manifest::fromYaml($yaml);
})->throws(InvalidManifestException::class, 'missing the required field: [export_paths]');

it('throws when `export_paths` is empty', function (): void {
    $yaml = <<<'YAML'
    name: "Test Kit"
    export_paths: []
    YAML;

    Manifest::fromYaml($yaml);
})->throws(InvalidManifestException::class, 'missing the required field: [export_paths]');

it('throws on invalid YAML syntax', function (): void {
    Manifest::fromYaml("name: [\ninvalid: yaml: content");
})->throws(InvalidManifestException::class, 'invalid YAML');

it('throws on non-mapping YAML content', function (): void {
    Manifest::fromYaml('just a string');
})->throws(InvalidManifestException::class, 'not a valid YAML mapping');

it('throws when manifest file does not exist', function (): void {
    Manifest::fromPath('/nonexistent/path/shopper-kit.yaml');
})->throws(ManifestNotFoundException::class);

it('parses dependencies in list-of-mappings format', function (): void {
    $yaml = <<<'YAML'
    name: "Test Kit"
    export_paths:
      - resources/views
    dependencies:
      - livewire/livewire: "^3.7"
      - livewire/flux: "^2.0"
      - shopper/stripe: "^2.7"
    YAML;

    $manifest = Manifest::fromYaml($yaml);

    expect($manifest->dependencies)->toBe([
        'livewire/livewire' => '^3.7',
        'livewire/flux' => '^2.0',
        'shopper/stripe' => '^2.7',
    ]);
});

it('parses the real livewire starter kit manifest', function (): void {
    $manifestPath = '/Users/chretiendev/Sites/OSS/shopperlabs/starters/packages/livewire-starter-kit/shopper-kit.yaml';

    if (! file_exists($manifestPath)) {
        $this->markTestSkipped('Livewire starter kit not available locally.');
    }

    $manifest = Manifest::fromPath($manifestPath);

    expect($manifest)
        ->name->toBe('Shopper Livewire Starter Kit')
        ->author->toBe('shopperlabs')
        ->dependencies->toHaveKey('livewire/livewire')
        ->exportPaths->toContain('resources/views');
});

it('ignores non-string dependency entries', function (): void {
    $yaml = <<<'YAML'
    name: "Test Kit"
    export_paths:
      - resources/views
    dependencies:
      livewire/livewire: "^3.0"
      invalid_entry: 123
    YAML;

    $manifest = Manifest::fromYaml($yaml);

    expect($manifest->dependencies)->toBe(['livewire/livewire' => '^3.0']);
});
