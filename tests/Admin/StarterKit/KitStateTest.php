<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Shopper\StarterKit\KitState;

beforeEach(function (): void {
    $this->tempDir = sys_get_temp_dir().'/shopper-kit-state-test-'.uniqid();
    mkdir($this->tempDir, 0755, true);
    $this->files = new Filesystem;
    $this->state = new KitState($this->files, $this->tempDir);
});

afterEach(function (): void {
    $this->files->deleteDirectory($this->tempDir);
});

it('returns false when no `.shopper-kit` file exists', function (): void {
    expect($this->state->exists())->toBeFalse();
});

it('writes and reads a `.shopper-kit` state file', function (): void {
    $this->state->write('shopperlabs/starter-blade', '1.0.0', [
        'resources/views/home.blade.php' => 'abc123',
        'routes/storefront.php' => 'def456',
    ]);

    expect($this->state->exists())->toBeTrue();

    $data = $this->state->read();

    expect($data)
        ->kit->toBe('shopperlabs/starter-blade')
        ->version->toBe('1.0.0')
        ->files->toBe([
            'resources/views/home.blade.php' => 'abc123',
            'routes/storefront.php' => 'def456',
        ]);

    expect($data['installed_at'])->not->toBeEmpty();
});

it('returns the installed kit ID', function (): void {
    expect($this->state->installedKit())->toBeNull();

    $this->state->write('shopperlabs/starter-blade', '1.0.0', []);

    expect($this->state->installedKit())->toBe('shopperlabs/starter-blade');
});

it('returns the installed version', function (): void {
    expect($this->state->installedVersion())->toBeNull();

    $this->state->write('shopperlabs/starter-blade', '2.1.0', []);

    expect($this->state->installedVersion())->toBe('2.1.0');
});

it('computes a SHA-256 hash for a file', function (): void {
    $filePath = $this->tempDir.'/test.txt';
    file_put_contents($filePath, 'hello world');

    $hash = KitState::hashFile($filePath);

    expect($hash)->toBe(hash('sha256', 'hello world'));
});

it('returns empty defaults when `.shopper-kit` contains invalid JSON', function (): void {
    file_put_contents($this->tempDir.'/.shopper-kit', 'not json');

    $data = $this->state->read();

    expect($data)
        ->kit->toBe('')
        ->version->toBe('')
        ->files->toBe([]);
});
