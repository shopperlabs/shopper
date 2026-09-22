<?php

declare(strict_types=1);

use Shopper\StarterKit\Exceptions\InvalidManifestException;
use Shopper\StarterKit\Exceptions\ManifestNotFoundException;
use Shopper\StarterKit\Manifest;

it('parses a valid YAML manifest', function (): void {
    $yaml = <<<'YAML'
    name: "Shopper Blade Starter"
    description: "A minimal Laravel/Blade storefront."
    stack: [inertia, react]
    version: "1.0.0"
    author: "shopperlabs"
    url: "https://github.com/shopperlabs/starter-blade"
    preview: "https://blade.shopperphp.com"
    docs: "https://docs.shopperphp.com/starter-kits/blade"
    screenshots:
      - .github/screenshots/home.png
      - .github/screenshots/checkout.png
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
        ->stack->toBe(['inertia', 'react'])
        ->version->toBe('1.0.0')
        ->author->toBe('shopperlabs')
        ->url->toBe('https://github.com/shopperlabs/starter-blade')
        ->preview->toBe('https://blade.shopperphp.com')
        ->docs->toBe('https://docs.shopperphp.com/starter-kits/blade')
        ->screenshots->toBe(['.github/screenshots/home.png', '.github/screenshots/checkout.png'])
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
        ->stack->toBe([])
        ->version->toBe('0.0.0')
        ->author->toBe('')
        ->url->toBe('')
        ->preview->toBe('')
        ->docs->toBe('')
        ->screenshots->toBe([])
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

it('parses a full manifest file from disk', function (): void {
    $manifest = Manifest::fromPath(__DIR__.'/fixtures/shopper-kit.yaml');

    expect($manifest)
        ->name->toBe('Shopper Livewire Starter Kit')
        ->author->toBe('shopperlabs')
        ->shopperConstraint->toBe('^3.0')
        ->dependencies->toHaveKeys(['livewire/livewire', 'livewire/flux', 'shopper/stripe'])
        ->devDependencies->toHaveKey('pestphp/pest')
        ->exportPaths->toContain('resources/views')
        ->postInstall->toContain('php artisan migrate');
});

it('keeps technologies outside the stack vocabulary and names them', function (): void {
    $yaml = <<<'YAML'
    name: "Test Kit"
    export_paths:
      - resources/views
    stack: [inertia, python, react]
    YAML;

    $manifest = Manifest::fromYaml($yaml);

    expect($manifest->stack)->toBe(['inertia', 'python', 'react'])
        ->and($manifest->unknownStack())->toBe(['python']);
});

it('drops non-string `stack` and `screenshots` entries', function (): void {
    $yaml = <<<'YAML'
    name: "Test Kit"
    export_paths:
      - resources/views
    stack: [inertia, 123, true, null]
    screenshots: [123, true, .github/screenshots/home.png]
    YAML;

    expect(Manifest::fromYaml($yaml))
        ->stack->toBe(['inertia'])
        ->screenshots->toBe(['.github/screenshots/home.png']);
});

it('dumps back to YAML with an inline stack and no empty section', function (): void {
    $yaml = <<<'YAML'
    name: "Test Kit"
    stack: [inertia, react]
    export_paths:
      - resources/views
    YAML;

    $dumped = Manifest::fromYaml($yaml)->toYaml();

    expect($dumped)
        ->toContain('stack: [inertia, react]')
        ->toContain("export_paths:\n  - resources/views")
        ->not->toContain('screenshots:')
        ->not->toContain('dependencies:')
        ->not->toContain('post_install:')
        ->and(Manifest::fromYaml($dumped)->stack)->toBe(['inertia', 'react']);
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
