<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Shopper\Core\Models\Setting;
use Shopper\Livewire\Pages\Settings\Appearance;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('system.settings');
    $this->actingAs($this->user);
});

describe(Appearance::class, function (): void {
    it('can render the appearance settings component', function (): void {
        Livewire::test(Appearance::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.appearance');
    });

    it('loads the active theme on mount', function (): void {
        Setting::query()->updateOrCreate(['key' => 'admin_theme'], [
            'value' => 'midnight',
            'display_name' => 'Admin Theme',
        ]);
        Cache::forget('shopper-setting.admin_theme');

        Livewire::test(Appearance::class)
            ->assertSet('theme', 'midnight');
    });

    it('defaults to the default theme when none is saved', function (): void {
        Livewire::test(Appearance::class)
            ->assertSet('theme', 'default');
    });

    it('persists the selected theme', function (): void {
        Livewire::test(Appearance::class)
            ->set('theme', 'midnight')
            ->call('store')
            ->assertDispatched('theme-saved');

        expect(Setting::query()->where('key', 'admin_theme')->value('value'))->toBe('midnight');
    });

    it('rejects an unknown theme and stores the default instead', function (): void {
        Livewire::test(Appearance::class)
            ->set('theme', 'removed-theme')
            ->call('store');

        expect(Setting::query()->where('key', 'admin_theme')->value('value'))->toBe('default');
    });
})->group('livewire', 'settings', 'theme');
