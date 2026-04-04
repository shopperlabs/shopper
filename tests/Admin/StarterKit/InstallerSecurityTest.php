<?php

declare(strict_types=1);

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Shopper\StarterKit\Installer;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->tempDir = sys_get_temp_dir().'/shopper-installer-test-'.uniqid();
    mkdir($this->tempDir, 0755, true);
    $this->installerFiles = new Filesystem;
});

afterEach(function (): void {
    $this->installerFiles->deleteDirectory($this->tempDir);
});

it('rejects paths containing path traversal sequences', function (): void {
    $command = new class extends Command
    {
        protected $name = 'test:command';
    };

    $installer = new Installer(
        command: $command,
        files: $this->installerFiles,
        basePath: $this->tempDir,
        kitId: 'test/kit',
        force: true,
    );

    $reflection = new ReflectionMethod($installer, 'hasPathTraversal');

    expect($reflection->invoke($installer, '../etc/passwd'))->toBeTrue()
        ->and($reflection->invoke($installer, 'some/../../etc/passwd'))->toBeTrue()
        ->and($reflection->invoke($installer, '/absolute/path'))->toBeTrue()
        ->and($reflection->invoke($installer, 'resources/views/home.blade.php'))->toBeFalse()
        ->and($reflection->invoke($installer, 'routes/storefront.php'))->toBeFalse();
});

it('identifies blacklisted paths', function (): void {
    $command = new class extends Command
    {
        protected $name = 'test:command';
    };

    $installer = new Installer(
        command: $command,
        files: $this->installerFiles,
        basePath: $this->tempDir,
        kitId: 'test/kit',
        force: true,
    );

    $reflection = new ReflectionMethod($installer, 'isBlacklisted');

    expect($reflection->invoke($installer, '.env'))->toBeTrue()
        ->and($reflection->invoke($installer, '.env.production'))->toBeTrue()
        ->and($reflection->invoke($installer, '.env.local'))->toBeTrue()
        ->and($reflection->invoke($installer, '.env.backup'))->toBeTrue()
        ->and($reflection->invoke($installer, 'composer.json'))->toBeTrue()
        ->and($reflection->invoke($installer, 'composer.lock'))->toBeTrue()
        ->and($reflection->invoke($installer, 'vendor'))->toBeTrue()
        ->and($reflection->invoke($installer, 'vendor/autoload.php'))->toBeTrue()
        ->and($reflection->invoke($installer, '.git'))->toBeTrue()
        ->and($reflection->invoke($installer, '.git/config'))->toBeTrue()
        ->and($reflection->invoke($installer, 'node_modules'))->toBeTrue()
        ->and($reflection->invoke($installer, 'node_modules/package/index.js'))->toBeTrue()
        ->and($reflection->invoke($installer, 'resources/views/home.blade.php'))->toBeFalse()
        ->and($reflection->invoke($installer, 'routes/web.php'))->toBeFalse()
        ->and($reflection->invoke($installer, 'config/storefront.php'))->toBeFalse();
});
