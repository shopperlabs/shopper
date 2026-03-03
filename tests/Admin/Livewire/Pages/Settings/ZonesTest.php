<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\Pages\Settings\Zones;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('access_setting');
    $this->actingAs($this->user);
});

describe(Zones::class, function (): void {
    it('can render zones settings component', function (): void {
        Livewire::test(Zones::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.zones');
    });

    it('can set current zone id', function (): void {
        $zone = Zone::factory()->create();

        Livewire::test(Zones::class)
            ->set('currentZoneId', $zone->id)
            ->assertSet('currentZoneId', $zone->id)
            ->assertDispatched('zone.changed');
    });

    it('passes zones to view', function (): void {
        Zone::factory()->count(2)->create();

        $component = Livewire::test(Zones::class);

        expect($component->viewData('zones')->count())->toBe(2);
    });
})->group('livewire', 'settings', 'zones');
