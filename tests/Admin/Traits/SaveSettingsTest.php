<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    Cache::flush();

    $this->subject = new class {
        use Shopper\Traits\SaveSettings {
            saveSettings as public;
        }
    };
});

describe('SaveSettings', function (): void {
    it('refuses to overwrite a locked setting with locked=false', function (): void {
        Setting::query()->create([
            'key' => 'name',
            'value' => 'Locked store',
            'display_name' => 'Store name',
            'locked' => true,
        ]);

        $this->subject->saveSettings(['name' => 'Tampered'], locked: false);

        expect(shopper_setting('name'))->toBe('Locked store');
    });

    it('invalidates default_currency cache when default_currency_id changes', function (): void {
        $eur = Currency::query()->where('code', 'EUR')->firstOrFail();
        $usd = Currency::query()->where('code', 'USD')->firstOrFail();

        Setting::query()->create([
            'key' => 'default_currency_id',
            'value' => $eur->id,
            'display_name' => 'Default currency',
            'locked' => true,
        ]);

        expect(shopper_currency())->toBe('EUR');

        $this->subject->saveSettings(['default_currency_id' => $usd->id]);

        expect(Cache::has('shopper-setting.default_currency'))->toBeFalse()
            ->and(shopper_currency())->toBe('USD');
    });

    it('runs a single read query for the existing locked column regardless of key count', function (): void {
        $this->subject->saveSettings([
            'name' => 'Acme',
            'email' => 'hello@acme.test',
            'phone_number' => '+1234567890',
        ]);

        expect(Setting::query()->count())->toBe(3);
    });
})->group('admin', 'traits');
