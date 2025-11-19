<?php

declare(strict_types=1);

use Shopper\Core\Models\Setting;

uses(Tests\TestCase::class);

describe(Setting::class, function (): void {
    it('has value cast as array', function (): void {
        $setting = Setting::factory()->create([
            'key' => 'test_key',
            'value' => ['option' => 'value'],
        ]);

        expect($setting->value)->toBeArray();
    });

    it('has locked attributes display names', function (): void {
        $displayName = Setting::lockedAttributesDisplayName('name');

        expect($displayName)->toBeString()->not->toBeEmpty();
    });

    it('hides locked attribute', function (): void {
        $setting = Setting::factory()->create(['key' => 'test', 'locked' => true]);

        expect($setting->toArray())->not->toHaveKey('locked');
    });

    it('returns correct display name for known keys', function (): void {
        $emailDisplay = Setting::lockedAttributesDisplayName('email');
        $logoDisplay = Setting::lockedAttributesDisplayName('logo');
        $countryDisplay = Setting::lockedAttributesDisplayName('country_id');

        expect($emailDisplay)->toBeString()
            ->and($logoDisplay)->toBeString()
            ->and($countryDisplay)->toBeString();
    });

    it('returns title case for unknown keys', function (): void {
        $displayName = Setting::lockedAttributesDisplayName('custom_key');

        expect($displayName)->toBe('Custom_Key');
    });

    it('casts locked to boolean', function (): void {
        $setting = Setting::factory()->create(['key' => 'test', 'locked' => 1]);

        expect($setting->locked)->toBeTrue()
            ->and($setting->locked)->toBeBool();
    });

    it('casts value to array', function (): void {
        $value = ['option1' => 'value1', 'option2' => 'value2'];
        $setting = Setting::factory()->create([
            'key' => 'test_settings',
            'value' => $value,
        ]);

        expect($setting->value)->toBeArray()
            ->and($setting->value)->toBe($value);
    });
})->group('setting', 'models');
