<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Shopper\StarterKit\Manifest;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->tempDir = sys_get_temp_dir().'/shopper-kit-export-test-'.uniqid();
    $this->kitDir = $this->tempDir.'/my-kit';

    mkdir($this->kitDir, 0755, true);
});

afterEach(function (): void {
    (new Filesystem)->deleteDirectory($this->tempDir);
});

function createManifest(string $kitDir, array $overrides = []): void
{
    $defaults = [
        'name' => 'Test Kit',
        'description' => 'A test starter kit',
        'version' => '1.0.0',
        'author' => 'acme',
        'url' => 'https://github.com/acme/test-kit',
        'shopper' => '^2.7',
        'php' => '^8.3',
        'laravel' => '^11.0|^12.0',
        'export_paths' => ['resources/views', 'routes/web.php'],
        'dependencies' => [],
        'post_install' => [],
        'stack' => [],
        'preview' => '',
        'docs' => '',
        'screenshots' => [],
    ];

    $data = array_merge($defaults, $overrides);

    $yaml = "name: \"{$data['name']}\"\n";
    $yaml .= "description: \"{$data['description']}\"\n";
    $yaml .= "version: \"{$data['version']}\"\n";
    $yaml .= "author: \"{$data['author']}\"\n";
    $yaml .= "url: \"{$data['url']}\"\n";
    $yaml .= "shopper: \"{$data['shopper']}\"\n";
    $yaml .= "php: \"{$data['php']}\"\n";
    $yaml .= "laravel: \"{$data['laravel']}\"\n";
    $yaml .= "export_paths:\n";

    foreach ($data['export_paths'] as $path) {
        $yaml .= "  - {$path}\n";
    }

    if ($data['stack'] !== []) {
        $yaml .= 'stack: ['.implode(', ', $data['stack'])."]\n";
    }

    foreach (['preview', 'docs'] as $link) {
        if ($data[$link] !== '') {
            $yaml .= "{$link}: \"{$data[$link]}\"\n";
        }
    }

    if ($data['screenshots'] !== []) {
        $yaml .= "screenshots:\n";

        foreach ($data['screenshots'] as $screenshot) {
            $yaml .= "  - {$screenshot}\n";
        }
    }

    file_put_contents($kitDir.'/shopper-kit.yaml', $yaml);
}

function createProjectFiles(string $baseDir): void
{
    @mkdir($baseDir.'/resources/views', 0755, true);
    @mkdir($baseDir.'/routes', 0755, true);

    file_put_contents($baseDir.'/resources/views/home.blade.php', '<h1>Home</h1>');
    file_put_contents($baseDir.'/resources/views/product.blade.php', '<h1>Product</h1>');
    file_put_contents($baseDir.'/routes/web.php', '<?php // routes');
}

it('exports project files to the kit directory', function (): void {
    createProjectFiles(base_path());
    createManifest($this->kitDir);

    $this->artisan('shopper:kit:export', ['path' => $this->kitDir])
        ->assertSuccessful();

    expect($this->kitDir.'/resources/views/home.blade.php')->toBeFile()
        ->and($this->kitDir.'/resources/views/product.blade.php')->toBeFile()
        ->and($this->kitDir.'/routes/web.php')->toBeFile()
        ->and($this->kitDir.'/shopper-kit.yaml')->toBeFile()
        ->and(file_get_contents($this->kitDir.'/resources/views/home.blade.php'))->toBe('<h1>Home</h1>');
});

it('fails when `shopper-kit.yaml` is missing from the kit directory', function (): void {
    $this->artisan('shopper:kit:export', ['path' => $this->kitDir])
        ->assertFailed();
});

it('fails when an export path does not exist in the project', function (): void {
    createManifest($this->kitDir, [
        'export_paths' => ['resources/views', 'app/NonExistent'],
    ]);

    createProjectFiles(base_path());

    $this->artisan('shopper:kit:export', ['path' => $this->kitDir])
        ->assertFailed();
});

it('fails when a forbidden path is in `export_paths`', function (): void {
    createManifest($this->kitDir, [
        'export_paths' => ['resources/views', '.env'],
    ]);

    createProjectFiles(base_path());

    $this->artisan('shopper:kit:export', ['path' => $this->kitDir])
        ->assertFailed();
});

it('clears the export path while preserving `.git`', function (): void {
    createProjectFiles(base_path());
    createManifest($this->kitDir);

    // Create a .git directory and an old file
    mkdir($this->kitDir.'/.git', 0755, true);
    file_put_contents($this->kitDir.'/.git/HEAD', 'ref: refs/heads/main');
    file_put_contents($this->kitDir.'/old-file.txt', 'should be deleted');

    $this->artisan('shopper:kit:export', ['path' => $this->kitDir, '--clear' => true])
        ->assertSuccessful();

    expect($this->kitDir.'/.git/HEAD')->toBeFile()
        ->and(file_get_contents($this->kitDir.'/.git/HEAD'))->toBe('ref: refs/heads/main')
        ->and($this->kitDir.'/old-file.txt')->not->toBeFile()
        ->and($this->kitDir.'/resources/views/home.blade.php')->toBeFile();
});

it('versions dependencies from the project `composer.json`', function (): void {
    createProjectFiles(base_path());
    createManifest($this->kitDir, [
        'export_paths' => ['resources/views'],
    ]);

    // Add a dependency that exists in the project's composer.json
    $yaml = file_get_contents($this->kitDir.'/shopper-kit.yaml');
    $yaml .= "\ndependencies:\n  laravel/framework: \"^11.0\"\n";
    file_put_contents($this->kitDir.'/shopper-kit.yaml', $yaml);

    $this->artisan('shopper:kit:export', ['path' => $this->kitDir])
        ->assertSuccessful();

    $exportedYaml = file_get_contents($this->kitDir.'/shopper-kit.yaml');

    // Should have picked up the real version constraint from the project's composer.json
    $composerJson = json_decode(file_get_contents(base_path('composer.json')), true);
    $realVersion = $composerJson['require']['laravel/framework'] ?? null;

    if ($realVersion === null) {
        expect($exportedYaml)->toContain('laravel/framework');

        return;
    }

    expect($exportedYaml)->toContain('laravel/framework')
        ->and($exportedYaml)->toContain($realVersion);
});

it('keeps the listing metadata when exporting', function (): void {
    createProjectFiles(base_path());
    createManifest($this->kitDir, [
        'export_paths' => ['resources/views'],
        'stack' => ['inertia', 'vue'],
        'preview' => 'https://demo.acme.test',
        'docs' => 'https://acme.test/docs',
        'screenshots' => ['.github/screenshots/03-cart.png', '.github/screenshots/01-home.png', '.github/screenshots/02-checkout.png'],
    ]);

    $this->artisan('shopper:kit:export', ['path' => $this->kitDir])
        ->assertSuccessful();

    $manifest = Manifest::fromPath($this->kitDir.'/shopper-kit.yaml');

    expect($manifest)
        ->stack->toBe(['inertia', 'vue'])
        ->preview->toBe('https://demo.acme.test')
        ->docs->toBe('https://acme.test/docs')
        ->screenshots->toBe(['.github/screenshots/03-cart.png', '.github/screenshots/01-home.png', '.github/screenshots/02-checkout.png']);
});

it('warns about a technology outside the stack vocabulary and keeps it', function (): void {
    createProjectFiles(base_path());
    createManifest($this->kitDir, ['export_paths' => ['resources/views'], 'stack' => ['inertia', 'python']]);

    $this->artisan('shopper:kit:export', ['path' => $this->kitDir])
        ->expectsOutputToContain('Stack [python] is not a known technology')
        ->assertSuccessful();

    expect(Manifest::fromPath($this->kitDir.'/shopper-kit.yaml')->stack)->toBe(['inertia', 'python']);
});

it('warns about a declared screenshot missing from the kit', function (): void {
    createProjectFiles(base_path());
    createManifest($this->kitDir, ['export_paths' => ['resources/views'], 'screenshots' => ['.github/screenshots/home.png']]);

    $this->artisan('shopper:kit:export', ['path' => $this->kitDir])
        ->expectsOutputToContain('Screenshot [.github/screenshots/home.png] does not exist')
        ->assertSuccessful();
});

it('keeps the kit files when clearing the export path', function (): void {
    createProjectFiles(base_path());
    createManifest($this->kitDir, ['export_paths' => ['resources/views'], 'screenshots' => ['.github/screenshots/home.png']]);

    mkdir($this->kitDir.'/.github/screenshots', 0755, true);
    file_put_contents($this->kitDir.'/.github/screenshots/home.png', 'png');
    file_put_contents($this->kitDir.'/README.md', '# Kit');
    file_put_contents($this->kitDir.'/stale.txt', 'should be deleted');

    $this->artisan('shopper:kit:export', ['path' => $this->kitDir, '--clear' => true])
        ->assertSuccessful();

    expect($this->kitDir.'/.github/screenshots/home.png')->toBeFile()
        ->and($this->kitDir.'/README.md')->toBeFile()
        ->and($this->kitDir.'/stale.txt')->not->toBeFile()
        ->and(Manifest::fromPath($this->kitDir.'/shopper-kit.yaml')->screenshots)->toBe(['.github/screenshots/home.png']);
});

it('asks to create the export directory if it does not exist', function (): void {
    $newDir = $this->tempDir.'/new-kit';

    $this->artisan('shopper:kit:export', ['path' => $newDir])
        ->expectsConfirmation("  Path [{$newDir}] does not exist. Create it?", 'yes')
        ->assertFailed(); // Fails because shopper-kit.yaml doesn't exist after dir creation

    expect($newDir)->toBeDirectory();
});
