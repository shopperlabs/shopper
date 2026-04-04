<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Shopper\StarterKit\Manifest;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->tempDir = sys_get_temp_dir().'/shopper-kit-init-test-'.uniqid();
    mkdir($this->tempDir, 0755, true);
});

afterEach(function (): void {
    (new Filesystem)->deleteDirectory($this->tempDir);
});

it('scaffolds a complete starter kit structure', function (): void {
    $kitDir = $this->tempDir.'/starter-test';

    $this->artisan('shopper:kit:init', ['--path' => $kitDir])
        ->expectsQuestion('Kit name', 'Test Storefront')
        ->expectsQuestion('Package name (vendor/name)', 'acme/starter-test')
        ->expectsQuestion('Description', 'A test storefront')
        ->expectsQuestion('Author', 'acme')
        ->assertSuccessful();

    expect($kitDir.'/composer.json')->toBeFile()
        ->and($kitDir.'/shopper-kit.yaml')->toBeFile()
        ->and($kitDir.'/README.md')->toBeFile()
        ->and($kitDir.'/resources/views/.gitkeep')->toBeFile()
        ->and($kitDir.'/resources/css/.gitkeep')->toBeFile()
        ->and($kitDir.'/resources/js/.gitkeep')->toBeFile()
        ->and($kitDir.'/routes/.gitkeep')->toBeFile();
});

it('generates valid JSON in `composer.json`', function (): void {
    $kitDir = $this->tempDir.'/starter-json';

    $this->artisan('shopper:kit:init', ['--path' => $kitDir])
        ->expectsQuestion('Kit name', 'JSON Test')
        ->expectsQuestion('Package name (vendor/name)', 'acme/starter-json')
        ->expectsQuestion('Description', 'Testing JSON output')
        ->expectsQuestion('Author', 'acme')
        ->assertSuccessful();

    $composerJson = json_decode(file_get_contents($kitDir.'/composer.json'), true);

    expect($composerJson)
        ->name->toBe('acme/starter-json')
        ->description->toBe('Testing JSON output')
        ->type->toBe('shopper-starter-kit')
        ->license->toBe('MIT');
});

it('generates a parsable `shopper-kit.yaml`', function (): void {
    $kitDir = $this->tempDir.'/starter-yaml';

    $this->artisan('shopper:kit:init', ['--path' => $kitDir])
        ->expectsQuestion('Kit name', 'YAML Test')
        ->expectsQuestion('Package name (vendor/name)', 'acme/starter-yaml')
        ->expectsQuestion('Description', 'Testing YAML output')
        ->expectsQuestion('Author', 'acme')
        ->assertSuccessful();

    $manifest = Manifest::fromPath($kitDir.'/shopper-kit.yaml');

    expect($manifest)
        ->name->toBe('YAML Test')
        ->description->toBe('Testing YAML output')
        ->version->toBe('1.0.0')
        ->author->toBe('acme')
        ->exportPaths->toBe(['resources/views', 'resources/css', 'resources/js', 'routes']);
});
