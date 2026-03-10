<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Setting;
use Shopper\Livewire\Pages\Settings\General;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('access_setting');
    $this->actingAs($this->user);
});

describe(General::class, function (): void {
    it('can render general settings component', function (): void {
        Livewire::test(General::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.general');
    });

    it('initializes form data with settings', function (): void {
        Setting::query()->updateOrCreate(
            ['key' => 'name'],
            ['value' => 'Test Store', 'display_name' => 'Store Name']
        );

        $component = Livewire::test(General::class);

        expect($component->get('data'))->toBeArray()
            ->and($component->get('data')['name'] ?? null)->toBe('Test Store');
    });

    it('initializes data property as array', function (): void {
        $component = Livewire::test(General::class);

        expect($component->get('data'))->toBeArray();
    });
})->group('livewire', 'settings');
