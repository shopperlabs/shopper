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
        ->expectsSearch('Stack', answer: ['livewire'], search: 'live', answers: ['livewire' => 'Livewire'])
        ->assertSuccessful();

    expect($kitDir.'/composer.json')->toBeFile()
        ->and($kitDir.'/shopper-kit.yaml')->toBeFile()
        ->and($kitDir.'/README.md')->toBeFile();
});

it('generates valid JSON in `composer.json`', function (): void {
    $kitDir = $this->tempDir.'/starter-json';

    $this->artisan('shopper:kit:init', ['--path' => $kitDir])
        ->expectsQuestion('Kit name', 'JSON Test')
        ->expectsQuestion('Package name (vendor/name)', 'acme/starter-json')
        ->expectsQuestion('Description', 'Testing JSON output')
        ->expectsQuestion('Author', 'acme')
        ->expectsSearch('Stack', answer: ['livewire'], search: 'live', answers: ['livewire' => 'Livewire'])
        ->assertSuccessful();

    $composerJson = json_decode(file_get_contents($kitDir.'/composer.json'), true);

    expect($composerJson)
        ->name->toBe('acme/starter-json')
        ->description->toBe('Testing JSON output')
        ->license->toBe('MIT')
        ->authors->toBe([['name' => 'acme']]);
});

it('generates a parsable `shopper-kit.yaml`', function (): void {
    $kitDir = $this->tempDir.'/starter-yaml';

    $this->artisan('shopper:kit:init', ['--path' => $kitDir])
        ->expectsQuestion('Kit name', 'YAML Test')
        ->expectsQuestion('Package name (vendor/name)', 'acme/starter-yaml')
        ->expectsQuestion('Description', 'Testing YAML output')
        ->expectsQuestion('Author', 'acme')
        ->expectsSearch('Stack', answer: ['livewire'], search: 'live', answers: ['livewire' => 'Livewire'])
        ->assertSuccessful();

    $manifest = Manifest::fromPath($kitDir.'/shopper-kit.yaml');

    expect($manifest)
        ->name->toBe('YAML Test')
        ->description->toBe('Testing YAML output')
        ->version->toBe('1.0.0')
        ->author->toBe('acme')
        ->stack->toBe(['livewire'])
        ->shopperConstraint->toBe('^3.0')
        ->screenshots->toBe([])
        ->exportPaths->toBe(['resources/views', 'resources/css', 'resources/js', 'routes']);
});

it('offers the whole stack vocabulary and keeps several choices', function (): void {
    $kitDir = $this->tempDir.'/starter-stacks';

    $this->artisan('shopper:kit:init', ['--path' => $kitDir])
        ->expectsQuestion('Kit name', 'Stacks Test')
        ->expectsQuestion('Package name (vendor/name)', 'acme/starter-stacks')
        ->expectsQuestion('Description', 'Testing several stacks')
        ->expectsQuestion('Author', 'acme')
        ->expectsSearch('Stack', answer: ['inertia', 'react'], search: '', answers: Manifest::STACKS)
        ->assertSuccessful();

    expect(Manifest::fromPath($kitDir.'/shopper-kit.yaml')->stack)->toBe(['inertia', 'react']);
});
