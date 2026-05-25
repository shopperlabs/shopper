<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Setting;
use Shopper\Livewire\Components\Dashboard\SetupGuide;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(SetupGuide::class, function (): void {
    it('skips computation when setup_guide_done is already set', function (): void {
        Setting::query()->create([
            'key' => 'setup_guide_done',
            'value' => true,
            'display_name' => 'Setup guide done',
            'locked' => true,
        ]);

        $this->actingAs(User::factory()->create(), config('shopper.auth.guard'));

        Livewire::test(SetupGuide::class)
            ->assertSet('dismissed', true)
            ->assertSet('steps', []);
    });

    it('does not auto-mark complete on mount when user lacks `access_setting`', function (): void {
        $this->actingAs(User::factory()->create(), config('shopper.auth.guard'));

        Livewire::test(SetupGuide::class);

        expect(Setting::query()->where('key', 'setup_guide_done')->exists())->toBeFalse();
    });

    it('rejects `complete()` for users without `access_setting`', function (): void {
        $this->actingAs(User::factory()->create(), config('shopper.auth.guard'));

        Livewire::test(SetupGuide::class)
            ->call('complete')
            ->assertStatus(403);
    });

    it('dispatches `setup-guide-completed` and writes `setup_guide_done`', function (): void {
        $admin = User::factory()->create();
        $admin->assignRole(config('shopper.admin.roles.admin'));

        $this->actingAs($admin, config('shopper.auth.guard'));

        Livewire::test(SetupGuide::class)
            ->call('complete')
            ->assertDispatched('setup-guide-completed')
            ->assertSet('dismissed', true);

        expect(shopper_setting('setup_guide_done'))->toBe(true);
    });
})->group('dashboard', 'setup-guide');
