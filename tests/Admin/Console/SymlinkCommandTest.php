<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->files = new Filesystem;
    $this->target = base_path('vendor/shopper/framework/public');
    $this->prefixLink = public_path(shopper()->prefix());

    $this->files->ensureDirectoryExists($this->target);
    $this->files->delete([public_path('vendor/shopper'), public_path('storage'), $this->prefixLink]);
});

afterEach(function (): void {
    $this->files->delete([public_path('vendor/shopper'), public_path('storage'), $this->prefixLink]);
    $this->files->deleteDirectory(base_path('vendor/shopper'));
});

it('links storage and the panel assets, and removes the link named after the prefix', function (): void {
    $this->files->link($this->target, $this->prefixLink);

    $this->artisan('shopper:link')->assertSuccessful();

    expect(realpath(public_path('vendor/shopper')))->toBe(realpath($this->target))
        ->and(is_link(public_path('storage')))->toBeTrue()
        ->and(is_link($this->prefixLink))->toBeFalse();
})->group('admin', 'console');

it('keeps a public directory named after the prefix that is not the assets link', function (): void {
    $this->files->ensureDirectoryExists($this->prefixLink);

    $this->artisan('shopper:link')->assertSuccessful();

    expect($this->prefixLink)->toBeDirectory();

    $this->files->deleteDirectory($this->prefixLink);
})->group('admin', 'console');
