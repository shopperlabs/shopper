<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Shopper\Core\Models\Setting;
use Shopper\Theme\Theme;
use Shopper\Theme\ThemeManager;

uses(Tests\Admin\TestCase::class);

it('builds a `Theme` from an array with sensible defaults', function (): void {
    $theme = Theme::fromArray('ocean', ['name' => 'Ocean']);

    expect($theme)
        ->id->toBe('ocean')
        ->name->toBe('Ocean')
        ->author->toBe('Shopper')
        ->hasDark->toBeTrue()
        ->css->toBeNull();
})->group('theme');

it('reads every configured attribute when building a `Theme`', function (): void {
    $theme = Theme::fromArray('ocean', [
        'name' => 'Ocean',
        'author' => 'John Doe',
        'has_dark' => false,
        'css' => 'https://cdn.test/ocean.css',
    ]);

    expect($theme)
        ->author->toBe('John Doe')
        ->hasDark->toBeFalse()
        ->css->toBe('https://cdn.test/ocean.css');
})->group('theme');

it('registers themes from configuration', function (): void {
    $manager = resolve(ThemeManager::class);

    expect($manager->all())->toBeInstanceOf(Collection::class)
        ->and($manager->has('default'))->toBeTrue()
        ->and($manager->has('midnight'))->toBeTrue()
        ->and($manager->find('default'))->toBeInstanceOf(Theme::class)
        ->and($manager->find('unknown'))->toBeNull();
})->group('theme');

it('registers a theme at runtime', function (): void {
    $manager = new ThemeManager;
    $manager->register(new Theme(id: 'ocean', name: 'Ocean'));

    expect($manager->has('ocean'))->toBeTrue()
        ->and($manager->find('ocean')->name)->toBe('Ocean');
})->group('theme');

it('resolves the default theme from configuration', function (): void {
    expect(resolve(ThemeManager::class)->default()->id)->toBe('default');
})->group('theme');

it('falls back to the default theme when no theme is selected', function (): void {
    Cache::forget('shopper-setting.admin_theme');

    expect(resolve(ThemeManager::class)->active()->id)->toBe('default');
})->group('theme');

it('resolves the active theme from the saved setting', function (): void {
    Setting::query()->updateOrCreate(['key' => 'admin_theme'], [
        'value' => 'midnight',
        'display_name' => 'Admin Theme',
    ]);
    Cache::forget('shopper-setting.admin_theme');

    expect(resolve(ThemeManager::class)->active()->id)->toBe('midnight');
})->group('theme');

it('falls back to the default theme when the saved theme is unknown', function (): void {
    Setting::query()->updateOrCreate(['key' => 'admin_theme'], [
        'value' => 'removed-theme',
        'display_name' => 'Admin Theme',
    ]);
    Cache::forget('shopper-setting.admin_theme');

    expect(resolve(ThemeManager::class)->active()->id)->toBe('default');
})->group('theme');
